<?php

namespace fanyou\service;

use api\modules\seller\models\forms\SystemLogModel;
use api\modules\seller\models\forms\SystemLogParamsModel;
use fanyou\enums\AppEnum;
use Yii;

/**
 * Class SystemLogService
 * @package fanyou\service
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-06-10 10:34
 */
class SystemLogService
{
    public static function addLog($method, $controllerId, $actionId, $userIp, $cache, $params, $type = AppEnum::SELLER)
    {
        if (!isset($_ENV['DB_DSN'])) {
            return;
        }
        //查询操作直接过滤
        if ($method === AppEnum::GET_REQUEST) {
            return;
        }
        //订时任务过滤
        if ($controllerId == 'task') {
            return;
        }
        //记录非查询操作
        $action = $actionId;//方法名称
        $info = $controllerId . '/' . $action;
        $model = new SystemLogModel();
        $model->controller = $controllerId;

        $model->service = $action;
        $model->method = $method;
        $model->path = $info;
        $model->ip = $userIp;
        $model->type = $type;
        if (!empty($cache)) {
            $model->merchant_id = $cache['merchantId'];
            $model->admin_id = $cache['account'];
            $model->admin_name = $cache['name'];
        }
        if ($model->insert()) {
            if (!empty($params)) {
                $detail = new SystemLogParamsModel();
                $detail->id = $model->primaryKey;
                $detail->params = json_encode($params, JSON_UNESCAPED_UNICODE);
                $detail->insert();
            }
        }

    }
}
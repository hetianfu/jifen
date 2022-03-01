<?php

namespace fanyou\queue;

use api\modules\mobile\models\forms\BasicOrderInfoModel;
use api\modules\mobile\service\BasicOrderInfoService;
use api\modules\seller\models\forms\SystemLogModel;
use api\modules\seller\models\forms\SystemLogParamsModel;
use fanyou\service\SystemLogService;
use Yii;
use yii\base\BaseObject;
use yii\queue\JobInterface;

/**
 * 系统日志
 * Class SystemLogJob
 * @package fanyou\queue
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-06-13 11:10
 */
class SystemLogJob extends BaseObject implements  JobInterface
{
    //控制器名称
    public $controllerId;
    //方法名称
    public $actionId;
    //用户IP
    public $userIp;
    //提交方式
    public $method;
    /**
     * 参数
     * @var
     */
    public $params;
    public $cache;
    public function __construct($controllerId,$actionId,$userIp,$method,$params )
    {
        $this->controllerId=$controllerId;
        $this->actionId=$actionId;
        $this->userIp=$userIp;
        $this->method=$method;
        $this->params=$params;
        $this->cache= Yii::$app->user->identity;
    }


    /**
     * @param \yii\queue\Queue $queue
     * @return mixed|void
     */
    public function execute($queue)
    {
        SystemLogService::addLog($this->method,$this->controllerId,$this->actionId,$this->userIp,$this->cache,$this->params);

    }
}
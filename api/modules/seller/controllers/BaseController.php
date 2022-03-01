<?php

namespace api\modules\seller\controllers;

use fanyou\components\casbin\CasbinFilePath;
use fanyou\components\casbin\Permission;
use fanyou\enums\AppEnum;
use fanyou\enums\HttpErrorEnum;
use fanyou\error\errorCode\ErrorUser;
use fanyou\error\FanYouHttpException;
use fanyou\queue\SystemLogJob;
use fanyou\service\SystemLogService;
use fanyou\tools\StringHelper;
use Yii;
use yii\rest\ActiveController;

class BaseController extends ActiveController
{

    public function init()
    {

        header('Access-Control-Allow-Origin:*');
        header('Access-Control-Allow-Methods:*');
        header('Access-Control-Allow-Headers:x-requested-with,content-type,Access-Token');
        header('Access-Control-Expose-Headers:error-msg');
        self::reloadLogs();
    }

    public function beforeAction($action)
    {
        parent::beforeAction($action);//进行父类重写
        $method = $this->getMethod();
        $this->checkPermission($method);
        //记录系统日志操作
        $ip = $_SERVER["REMOTE_ADDR"];//用户IP

        if (Yii::$app->params[AppEnum::QUEUE]) {
            $job = new SystemLogJob(
                Yii::$app->controller->id,
                $this->action->id,
                $ip, $method,
                $this->getParams($method)
            );
            Yii::$app->queue->push($job);
        } else {
            SystemLogService::addLog($method, Yii::$app->controller->id,
                $this->action->id, $ip, Yii::$app->user->identity, $this->getParams($method));
        }
        return true;
    }
    public function afterAction($action, $result)
    {
        if(is_null($result)){
            return null;
        }
        return StringHelper::toCamelize($result);
    }
    /**
     * 取第一条错误信息
     * @param $model
     * @return bool|mixed
     */
    public static function getModelError($model)
    {
        $errors = $model->getErrors();
        if (!is_array($errors)) {
            return true;
        }
        $firstError = array_shift($errors);
        if (!is_array($firstError)) {
            return true;
        }
        return array_shift($firstError);
    }

    public function getMerchantId()
    {
        return Yii::$app->user->identity['merchantId'];
    }

    public function getShopId()
    {
        return Yii::$app->user->identity['shopId'];
    }

    public function getRoleIds()
    {
        return Yii::$app->user->identity['roleIds'];
    }

    public function getAccountId()
    {
        return Yii::$app->user->identity['account'];
    }

    public function getUserCache()
    {
        return Yii::$app->user->identity;
    }

    public function isAdmin()
    {
        return Yii::$app->user->identity['isAdmin'];
    }

    /**
     * 获取Request的params
     * @param bool $withShopId
     * @param bool $unCamelize
     * @return array|mixed
     */
    public function getRequestPost($withShopId = true, $unCamelize = true)
    {
        $params = Yii::$app->request->post();
        if ($withShopId) {
            $userCache = Yii::$app->user->identity;
            //  $params['merchant_id'] = $userCache['merchantId'];
            $params['shop_id'] = $userCache['shopId'];
        }
        if ($unCamelize) {
            return StringHelper::toUnCamelize($params);
        }
        return $params;
    }

    public function getRequestGet($withShopId = true, $unCamelize = true)
    {
        $params = Yii::$app->request->get();
        if ($withShopId) {
            $userCache = $this->getUserCache();
            //    $params['merchant_id'] = $userCache['merchantId'];
            $params['shop_id'] = $userCache['shopId'];
        }
        if ($unCamelize) {
            return StringHelper::toUnCamelize($params);
        }
        return $params;
    }

    public function postRequestId($param = 'id')
    {
        return Yii::$app->request->post($param);
    }

    public function getRequestId($param = 'id')
    {
        return Yii::$app->request->get($param);
    }

    public function getEmptyPage()
    {
        $result['list'] = [];
        $result['totalCount'] = 0;
        return $result;
    }

    public function getMethod()
    {
        $method = 'GET';
        if (Yii::$app->request->isPost) {
            $method = 'POST';

        } else if (Yii::$app->request->isPatch) {
            $method = 'PATCH';
        } else if (Yii::$app->request->isDelete) {
            $method = 'DELETE';
        }
        return $method;
    }

    public function getParams($method = 'DELETE')
    {
        switch ($method) {
            case 'POST':
            case 'PUT':
            case 'PATCH':
                return Yii::$app->request->post();
            default:
                return Yii::$app->request->get();
        }
    }

    /**
     * 默认日志的上下文会包括$_GET, $_POST,$_FILES, $_COOKIE, $_SESSION, $_SERVER这些信息，而这些信息是可以配置的
     * 的日志目标配置指明了只有 $_SERVER 变量的值将被追加到日志消息中。
     */
    static function reloadLogs()
    {
        Yii::$app->log->targets[] = Yii::createObject([
            'class' => 'yii\log\FileTarget',
            'levels' => ['error'],
            'logFile' => '@runtime/logs/seller/error/' . date('Ymd') . '.log', //自定义文件路径
            'logVars' => ['_SERVER'],  //只记录_GET _POST的值
            'maxFileSize' => 1024 * 10,  // 日志文件大小
            'maxLogFiles' => 10,  // 日志个数
            'prefix' => function () {
                $ip = $_SERVER["REMOTE_ADDR"];
                $controller = Yii::$app->controller->id;
                $action = Yii::$app->controller->action->id;
                return "[$ip][$controller][$action]";
            }
        ]);
        Yii::$app->log->init();
    }

    /**
     * 较验权限
     * @param $method
     * @throws FanYouHttpException
     * @throws \yii\base\InvalidConfigException
     */
    private function checkPermission($method)
    {
        //如果是管理者，或者未登陆 则不较验
        if ($this->isAdmin() || empty($this->getUserCache())) {
            return;
        }
        $thisUrl = '/' . Yii::$app->request->getPathInfo();
        if (self::withoutCheckPermission($thisUrl)) {
            return;
        }
        //否则较验权限
        $fileName = $this->getRoleIds()[0];
        //获取第一个角色
        $permission = new Permission($fileName);
        if (file_exists(CasbinFilePath::getFileCsv($fileName))) {
            $isExists = $permission->enforce('role_' . $fileName, $thisUrl, $method);
            if ($isExists) {
                //只有文件存在，且验证通过才返回
                return;
            }
        }
        //抛出异常，权限验证不通过
        throw new FanYouHttpException(HttpErrorEnum::Expectation_Failed, ErrorUser::USER_NO_POWER);
    }

    /**
     * 免较验权限接口
     * @param $requestUrl
     * @return bool
     */
    private static function withoutCheckPermission($requestUrl)
    {
        $array = Yii::$app->params['checkPermission']['login'];
        return in_array($requestUrl, $array);
    }
}

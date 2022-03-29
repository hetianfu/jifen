<?php

namespace api\modules\manage\controllers;

use fanyou\enums\AppEnum;
use fanyou\queue\SystemLogJob;
use fanyou\service\SystemLogService;
use fanyou\tools\StringHelper;
use yii\rest\ActiveController;
use Yii;
use yii\web\UnprocessableEntityHttpException;

class BaseController extends ActiveController
{
    public function init(){
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
        //记录非查询操作
        $ip = $_SERVER["REMOTE_ADDR"];//用户IP
        if( Yii::$app->params[AppEnum::QUEUE]){
            $job = new SystemLogJob(
                Yii::$app->controller->id,
                $this->action->id,
                $ip,$method,
                $this->getParams($method)
            );
            Yii::$app->queue->push($job);
        }else{
            SystemLogService::addLog($method,Yii::$app->controller->id,
                $this->action->id,$ip,Yii::$app->user->identity,
                $this->getParams($method),AppEnum::MANAGE);
        }
        return true;
    }
    public function afterAction($action, $result)
    {
        if(empty($result)){
            return null;
        }
        return StringHelper::toCamelize($result);
    }
    /**
     * 取第一条错误信息
     * @param $model
     * @return bool|mixed
     */
    public static function getModelError($model) {
        $errors = $model->getErrors();
        if(!is_array($errors)){
            return true;
        }
        $firstError = array_shift($errors);
        if(!is_array($firstError)) {
            return true;
        }
        return array_shift($firstError);
    }
    public function  getMerchantId(){
        return  $this->getUserCache()['merchant_id'];
    }
    public function  getShopId(){
        return $this->getUserCache()['id'];
    }
    public function  getUserCache(){
       return  Yii::$app->user->identity;
    }
    public function  getOpenId(){
        return  Yii::$app->user->identity['open_id'];
    }
    public function  getRequestPost($withShopId=true,$unCamelize=true){
        $params=Yii::$app->request->post();
        if($withShopId){
            $userCache = Yii::$app->user->identity;
            $params['merchant_id'] =$userCache['merchant_id'];
            $params['user_id'] =$userCache['user_id'];
            $params['shop_id'] =$userCache['shop_id'];
         }
        if($unCamelize){
            return StringHelper::toUnCamelize($params);
        }
        return $params;
    }
    public function  getRequestGet($withShopId=true,$unCamelize=true){

        $params=Yii::$app->request->get();
        if($withShopId) {
            $userCache = Yii::$app->user->identity;
            $params['merchant_id'] =$userCache['merchant_id'];
            $params['user_id'] =$userCache['user_id'];
            $params['shop_id'] =$userCache['shop_id'];
            $params['check_employee_id'] =$userCache['id'];
        }
        if($unCamelize){
            return StringHelper::toUnCamelize($params);
        }
        return $params;
    }
    public function  getRequestId($id='id'){

        return Yii::$app->request->get($id);
    }


    public function  throwError($model){
        throw new  UnprocessableEntityHttpException($this->getModelError($model));
    }


    public   function getMethod()
    {   $method='GET';
        if(Yii::$app->request->isPost){
            $method='POST';
        }else if(Yii::$app->request->isPatch){
            $method='PATCH';
        }else if(Yii::$app->request->isPut){
            $method='PATCH';
        }else if(Yii::$app->request->isDelete){
            $method='DELETE';
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
    static function reloadLogs(){
        Yii::$app->log->targets[]=  Yii::createObject(  [
            'class' => 'yii\log\FileTarget',
            'levels' => ['info' ],
            'logFile' => '@runtime/logs/manage/'.date('Ymd').'-info.log', //自定义文件路径
            'logVars' => ['_POST'],  //只记录 _POST的值
            'maxFileSize' => 1024 * 10,  // 日志文件大小
            'maxLogFiles' => 10,  // 日志个数
        ] );
        Yii::$app->log->targets[]=Yii::createObject([
            'class' => 'yii\log\FileTarget',
            'levels' => ['error' ],
            'logFile' => '@runtime/logs/manage/'.date('Ymd').'-error.log', //自定义文件路径
            'logVars' => ['*'],  //只记录_GET _POST的值
            'maxFileSize' => 1024 * 10,  // 日志文件大小
            'maxLogFiles' => 10,  // 日志个数
        ] );
    }

}

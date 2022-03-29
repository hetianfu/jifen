<?php

namespace api\modules\mobile\controllers;

use fanyou\enums\AppEnum;
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

      $sourceId = Yii::$app->request->post('sourceId');
      if(!$sourceId){
        $sourceId = Yii::$app->request->get('sourceId');
      }
      if(!$sourceId){
        $sourceId = 1;
      }
      Yii::$app->session->set('sourceId',$sourceId);
    }

    public function beforeAction($action)
    {
        parent::beforeAction($action);//进行父类重写
//        $method = $this->getMethod();
        //记录非查询操作
//        $ip = $_SERVER["REMOTE_ADDR"];//用户IP
//        if( Yii::$app->params[AppEnum::QUEUE]){
//            $job = new SystemLogJob(
//                Yii::$app->controller->id,
//                $this->action->id,
//                $ip,$method,
//                $this->getParams($method)
//            );
//            Yii::$app->queue->push($job);
//        }else{
//            SystemLogService::addLog($method,Yii::$app->controller->id,
//                 $this->action->id,$ip,Yii::$app->user->identity,
//                 $this->getParams($method),AppEnum::MOBILE);
//        }
        return true;
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

    public function getUserCache()
    {
      if(Yii::$app->user->identity){
        return Yii::$app->user->identity;
      }else{
        return ['id'=>'','mini_app_open_id'=>'','openId'=>'','nick_name'=>'','head_img'=>'','is_vip'=>''];
      }
    }
    public function getUserCacheByName($name)
    {
        return $this->getUserCache()[$name];
    }

    public function getUserId()
    {
        return (string)$this->getUserCache()['id'];
   }

    public function getMiniAppOpenId()
    {
        return $this->getUserCache()['mini_app_open_id'];
    }

    public function getMpOpenId()
    {
      if(isset($this->getUserCache()['openId'])){
        return $this->getUserCache()['openId'];
      }else{
        return '';
      }

    }
    public function getRequestPost($withUserId = true,$unCamelize=false)
    {
        $params = Yii::$app->request->post();
        if ($withUserId) {
            $userCache = $this->getUserCache();
            $params['user_id'] = (string)$userCache['id'];
            $params['nick_name'] = $userCache['nick_name'];
            $params['head_img'] = $userCache['head_img'];
            $params['is_vip'] = $userCache['is_vip'];
        }
        if($unCamelize){
            return StringHelper::toUnCamelize($params);
        }
        return $params;
    }

    public function getRequestGet($withUserId = true,$unCamelize=false)
    {
        $params = Yii::$app->request->get();
        if ($withUserId) {
            $userCache = $this->getUserCache();
            $params['userId'] = (string)$userCache['id'];
            $params['nickName'] = $userCache['nick_name'];
            $params['headImg'] = $userCache['head_img'];
            $params['isVip'] = $userCache['is_vip'];
        }
        if($unCamelize){
            return StringHelper::toUnCamelize($params);
        }
        return $params;
    }

    public function createOutTradeNo($start, $end)
    {
        return date("YmdHis", time()) . '' . rand($start, $end);
    }

    public function createRandomOutTradeNo()
    {
        return $this->createOutTradeNo(10000, 99999);
    }
    public function  getRequestId($id='id'){

        return Yii::$app->request->get($id);
    }
    public function setCache($key, $data, $time = 600)
    {
        return Yii::$app->cache->set($key, json_encode($data), $time);
    }
    public function getCache($key)
    {
        return Yii::$app->cache->get($key);
    }

    public function deleteCache($key)
    {
        return Yii::$app->cache->delete($key);
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

    public function getEmptyPage()
    {
        $result['list'] = [];
        $result['totalCount'] = 0;
        return $result;
    }

    /**
     * 默认日志的上下文会包括$_GET, $_POST,$_FILES, $_COOKIE, $_SESSION, $_SERVER这些信息，而这些信息是可以配置的
     * 的日志目标配置指明了只有 $_SERVER 变量的值将被追加到日志消息中。
     */
    static function reloadLogs(){
        Yii::$app->log->targets[]=Yii::createObject([
            'class' => 'yii\log\FileTarget',
            'levels' => ['error' ],
            'logFile' => '@runtime/logs/mobile/'.date('Ymd').'-error.log', //自定义文件路径
            'logVars' => ['*'],  //只记录_GET _POST的值
            'maxFileSize' => 1024 * 10,  // 日志文件大小
            'maxLogFiles' => 10,  // 日志个数
        ] );
    }
}

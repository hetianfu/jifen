<?php
//
//namespace api\modules\seller\controllers\common;
//
//use api\modules\auth\ApiAuth;
//use api\modules\seller\controllers\BaseController;
//use api\modules\seller\models\wechat\Menu;
//use api\modules\seller\service\wechat\MenuService;
//use common\helpers\ResultHelper;
//use Yii;
//
///**
// * 服务事件
// *
// * Class MenuController
// * @package addons\Wechat\merchant\controllers
// * @author jianyuan <admin@163.com>
// */
//class WxServiceController extends BaseController
//{
//    private $service;
//    public function init()
//    {
//        parent::init();
//        $this->service = new MenuService();
//    }
//
//    public function behaviors()
//    {
//        $behaviors = parent::behaviors();
//        $behaviors['authenticator'] = [
//            'class' => ApiAuth::class,
//            'optional'=>['push']
//        ];
//        return $behaviors;
//    }
//
//    public function actionPush()
//    {
//        Yii::$app->wechat->app->server->push(function ($message) {
//            // $message['FromUserName'] // 用户的 openid
//            // $message['MsgType'] // 消息类型：event, text....
//            return "您好！我的林海";
//        });
//        return 11111;
//    //  Yii::$app->wechat->app->server->push(ImageMessageHandler::class,Message::IMAGE);
//
//    //  $response= Yii::$app->wechat->app->server->serve();
//     //  return $response->send();
//    }
//
//}
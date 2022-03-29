<?php

namespace api\modules\seller\controllers;

use api\modules\auth\ApiAuth;
use api\modules\seller\models\forms\WechatUserModel;
use api\modules\seller\service\wechat\MenuService;
use api\modules\seller\service\WechatUserService;
use Yii;

/**
 * 服务事件
 *
 * Class MenuController
 * @package addons\Wechat\merchant\controllers
 * @author jianyuan <admin@163.com>
 */
class WxServiceController extends BaseController
{
    private $service;
    public function init()
    {
        parent::init();
        $this->service = new MenuService();

    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => ApiAuth::class,
            'optional'=>['push','bind']
        ];
        return $behaviors;
    }
    public function actionBind()
    {
        echo Yii::$app->request->get("echostr");exit;
    }
    public function actionPush()
    {
        Yii::$app->wechat->app->server->push(function ($message) {
            switch ($message['MsgType']) {
                case 'event':

                    $service=new WechatUserService();
                    $model=new WechatUserModel();
                    $model->openid= $message['FromUserName'];
                    $model->insert();
                    return '欢迎关注，您有新的礼包请领取';
                    break;
                case 'text':
                    $s=$message['FromUserName'];
                    return '收到文字消息';
                    break;
                case 'image':
                    return '收到图片消息';
                    break;
                case 'voice':
                    return '收到语音消息';
                    break;
                case 'video':
                    return '收到视频消息';
                    break;
                case 'location':
                    return '收到坐标消息';
                    break;
                case 'link':
                    return '收到链接消息';
                    break;
                case 'file':
                    return '收到文件消息';
                // ... 其它消息
                default:
                    return '收到其它消息';
                    break;
            }

        });

        $response = Yii::$app->wechat->app->server->serve();
        $response->send();


    }

}
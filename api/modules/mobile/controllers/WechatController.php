<?php

namespace api\modules\mobile\controllers;

use api\modules\auth\ApiAuth;
use fanyou\components\SystemConfig;
use fanyou\enums\BaseEnum;
use fanyou\enums\entity\BasicConfigEnum;
use fanyou\enums\entity\WechatConfigEnum;
use fanyou\enums\SystemConfigEnum;
use fanyou\error\FanYouHttpException;
use fanyou\service\FanYouSystemGroupService;
use fanyou\tools\helpers\Url;
use Yii;

/**
 * 微信公众号网页授权
 * Class WechatController
 * @package api\modules\manage\controllers
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-06-19 9:14
 */
class WechatController extends BaseController
{

    private $configService;

    public function init()
    {
        parent::init();
        $this->configService = new SystemConfig();
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => ApiAuth::class,
            'optional' => ['oauth', 'oauth-redirect', 'code-to-user', 'get-js-sdk']
        ];
        return $behaviors;
    }


    /**
     * 网页授权
     * @throws FanYouHttpException
     */
    public function actionOauth()
    {
//        Yii::$app->wechat->app->oauth->scopes(['snsapi_userinfo'])
//            ->redirect('http://wechat-mini-app-shop.xunfengzhijia.com/api/mobile/wechat/oauth-redirect' . '?barCode=' . Yii::$app->request->get('barCode'))->send();
        $domain = FanYouSystemGroupService::getDm();
        Yii::$app->wechat->app->oauth->scopes(['snsapi_userinfo'])
            ->redirect($domain . 'api/mobile/wechat/oauth-redirect?barCode=' . Yii::$app->request->get('barCode'))->send();

    }
    /**
     * 回调地址重定向
     */
    public function actionOauthRedirect()
    {
        $domain = FanYouSystemGroupService::getDm();
        $code = Yii::$app->request->get('code');
        $barCode = Yii::$app->request->get('barCode');
       // header("Location: " . $domain . "pages/tabbar/home/index?code=" . $code . '&barCode=' . $barCode);
        header("Location: ".$domain ."pages/auth/index?code=".$code.'&barCode='.$barCode);
    }

    /**
     * 用户Code换取用户信息
     * @return array
     */
    public function actionCodeToUser()
    {
        return Yii::$app->wechat->app->oauth->user()->toArray();
        //后期升级直播需要 更新wechat版本，则相应接口发生如下改变
        // return Yii::$app->wechat->app->oauth->userFromCode(parent::getRequestId('code'))->toArray();
    }

    /**
     * jsSdk数组
     * @return array|string
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function actionGetJsSdk()
    {
        Yii::$app->wechat->app->jssdk->setUrl(parent::getRequestId('url'));

        return Yii::$app->wechat->app->jssdk->getConfigArray(json_decode(parent::getRequestId('jsApiList')));

    }

}
/**********************End Of UserInfo 控制层************************************/



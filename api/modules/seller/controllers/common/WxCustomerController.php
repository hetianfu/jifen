<?php

namespace api\modules\seller\controllers\common;

use api\modules\auth\ApiAuth;
use api\modules\seller\controllers\BaseController;
use fanyou\enums\HttpErrorEnum;
use fanyou\error\FanYouHttpException;
use Yii;

/**
 * 微信客服管理
 *
 * Class MenuController
 * @package addons\Wechat\merchant\controllers
 * @author jianyuan <admin@163.com>
 */
class WxCustomerController extends BaseController
{
    private $service;
    public function init()
    {
        parent::init();

    }    public function behaviors()
{
    $behaviors = parent::behaviors();
    $behaviors['authenticator'] = [
        'class' => ApiAuth::class,
        'optional' => ['invite-wx-customer','get-wx-customer-list',
            'add-wx-customer','update-wx-customer',
            'set-wx-customer-head-img','send-customer-message','create-message']
    ];
    return $behaviors;
}
    /**
     * 添加客服
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function actionAddWxCustomer()
    {
        //  完整客服帐号，格式为：帐号前缀@公众号微信号
        $account= Yii::$app->request->post('account');
        $nickName=Yii::$app->request->post('nickName');
        return Yii::$app->wechat->app->customer_service->create($account,$nickName);
    }

    /**
     *  设置客服头像
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function actionSetWxCustomerHeadImg()
    {
        $account=Yii::$app->request->post('account');
        $avatarPath=Yii::$app->request->post('avatarPath');
        return Yii::$app->wechat->app->customer_service->setAvatar($account,$avatarPath);
    }
    /**
     * 邀请微信用户加入客服
     * @return mixed
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function actionInviteWxCustomer()
    {
        //以账号 foo@test 邀请 微信号 为 xxxx 的微信用户加入客服。
        $account=Yii::$app->request->post('account');
        $wxName=Yii::$app->request->post('wxName');
        return Yii::$app->wechat->app->customer_service->invite($account,$wxName);
    }

    /**
     * 修改客服名称
     * @return mixed
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function actionUpdateWxCustomer()
    {
        $account='kf2003@gh_17a20461afb7';//Yii::$app->request->post("account");
        $nickName='yuan';//Yii::$app->request->post("nickName");
        return Yii::$app->wechat->miniProgram->customer_service->update($account,$nickName);
    }

    /**
     * 客服必须在线，且用户最近与平台交互
     * @return mixed
     * @throws FanYouHttpException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function actionCreateMessage()
    {
        $account= 'kf2003@gh_17a20461afb7';// Yii::$app->request->post("account");
        $message='我是刘星宇，我已成为客服，等你哟！';//Yii::$app->request->post('message');
        $openId='HfeBR2bIuiqpHuSI04';
        $result=Yii::$app->wechat->miniProgram->customer_service->create($account,$openId);
        if($result['errcode']){
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,$result['errmsg']);
        }
        return $result ;
    }

    public function actionSendCustomerMessage()
    {
        $account= 'kf2003@gh_17a20461afb7';// Yii::$app->request->post("account");
        $message='我是刘星海，我已成为客服，等你哟！';//Yii::$app->request->post('message');
        $openId='o3bOq5a0D-HfeBR2bIuiqpHuSI04';
        $result=Yii::$app->wechat->miniProgram->customer_service->message($message)->from($account) ->to($openId)->send();
        if($result['errcode']){
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,$result['errmsg']);
        }
      return $result ;
    }

    /**
     *  删除客服
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function actionDelWxCustomer()
    {
        $account=Yii::$app->request->post("account");
        return Yii::$app->wechat->app->customer_service->delete($account);
    }
    /**
     * 获取所有客服
     * @return mixed
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     */
    public function actionGetWxCustomerList()
    {
        return  Yii::$app->wechat->miniProgram->customer_service->list();
    }

    /**
     * 获取所有在线的客服
     * @return mixed
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     */
    public function actionGetWxCustomerOnlineList()
    {
        return Yii::$app->wechat->app->customer_service->online();
    }
}
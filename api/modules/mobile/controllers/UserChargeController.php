<?php
namespace api\modules\mobile\controllers;

use api\modules\auth\ApiAuth;
use api\modules\mobile\models\forms\UserInfoModel;
use api\modules\mobile\models\forms\UserVipDetailModel;
use api\modules\mobile\models\forms\UserVipModel;
use api\modules\mobile\models\forms\UserWalletDetailModel;
use api\modules\mobile\models\forms\WxPayRequest;
use api\modules\mobile\service\UserInfoService;
use api\modules\mobile\service\UserVipService;
use api\modules\mobile\service\UserWalletDetailService;
use api\modules\mobile\service\WxPayService;
use fanyou\components\SystemConfig;
use fanyou\enums\AppEnum;
use fanyou\enums\entity\BasicConfigEnum;
use fanyou\enums\HttpErrorEnum;
use fanyou\enums\StatusEnum;
use fanyou\enums\SystemConfigEnum;
use fanyou\enums\WalletStatusEnum;
use fanyou\enums\WxNotifyEnum;
use fanyou\error\FanYouHttpException;
use fanyou\tools\StringHelper;
use Yii;

/**
 *  UserScore
 *  @author  Round
 *  @E-mail: Administrator@qq.com
 *
 */
class UserChargeController extends BaseController
{

    private $service;
    private $walletDetailService;
    private $wxPayService;
    private  $configService;
    private $userVipService;
    public function init()
    {
        parent::init();
        $this->service = new UserInfoService();
        $this->walletDetailService=new UserWalletDetailService();
        $this->wxPayService = new WxPayService();
        $this->configService=new SystemConfig();
        $this->userVipService = new UserVipService();
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => ApiAuth::class,
            'optional' => ['wx-pay-notify','vip-pay-notify']
        ];
        return $behaviors;
    }
/********************* 充值模块控制层************************************/

    /**
     * 购买会员
     * @return mixed
     * @throws \Exception
     */
    public function actionBuyVip()
    {
        $basicConfig=$this->configService->getConfigInfoValue(false,SystemConfigEnum::BASIC_CONFIG);

        if(is_null($basicConfig) || empty($basicConfig[BasicConfigEnum::BASIC_SITE]) ){
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,"请配置正常的后台域名地址");
        }
        $vipPayId=Yii::$app->request->post('vipPayId');

        $model = new WxPayRequest(parent::getMiniAppOpenId(),"充值VIP");
        $model->total_fee = Yii::$app->request->post('amount')*100 ;
        $model->notify_url=$basicConfig[BasicConfigEnum::BASIC_SITE].'api/mobile/user-charges/vip-pay-notify';
        $model->out_trade_no = StringHelper::uuid();
        $model->attach=parent::getUserId();
        $array = $model->toArray();

        $prePayId = $this->wxPayService->getPrePayId($array);

        if ($prePayId) {
            $result = $this->wxPayService->getJsSdk($prePayId);
            return $result;
        }

    }

    /**
     * 微信VIP充值回调
     * @return string
     * @throws \Throwable
     */
    public function actionVipPayNotify()
    {
        //获取返回的xml
        $testxml = file_get_contents("php://input");
        //将xml转化为json格式
        $jsonxml = json_encode(simplexml_load_string($testxml, 'SimpleXMLElement', LIBXML_NOCDATA));
        //转成数组
        $data = json_decode($jsonxml, true);
        //保存微信服务器返回的签名sign
        $data_sign = $data['sign'];
        $sign = $this->wxPayService->makeSign($data);
        //判断签名是否正确,判断支付状态
        if (($sign === $data_sign) && ($data['return_code'] == 'SUCCESS') && ($data['result_code'] == 'SUCCESS')) {
            $results = $data;
            //获取服务器返回的数据
            $order_sn = $data['out_trade_no'];    //订单号
            $attach = $data['attach'];        //附加参数,选择传递订单ID
            $openid = $data['openid'];          //付款人openID
            $total_fee = (int)$data['total_fee']   ;    //付款金额

            $model =new  UserVipDetailModel();
            $model->relation_id=$order_sn;
            $model->user_id=$attach;

            $userInfo=$this->service->getOneById( $attach);
            $model->nick_name=$userInfo['nick_name'];

            $model->type=WalletStatusEnum::VIP;
            $model->amount= number_format($total_fee/100,2);
            $model->content='充值商城会员！';
            if($model->insert()){
                UserInfoModel::updateAll(['is_vip'=>StatusEnum::ENABLED],['id'=>$attach]);
                $old=$this->userVipService->getOneById($attach);
                if(empty($old)){
                $vip=new  UserVipModel();
                $vip->id=$attach;
                $vip->is_permanent=StatusEnum::ENABLED;
                $vip->is_vip=StatusEnum::ENABLED;
                $vip->merchant_id=AppEnum::MERCHANTID;
                $this->userVipService->addUserVip($vip);
                }
            }

        } else {
            $results = false;
        }
        //返回状态给微信服务器
        if ($results) {
            $str = WxNotifyEnum::SUCCESS;
        } else {
            $str = WxNotifyEnum::FAIL;
        }
        return $str;

    }
    /**
     * 钱包充值
     * @return mixed
     * @throws \Exception
     */
    public function actionCharge()
    {  $basicConfig=$this->configService->getConfigInfoValue(false,SystemConfigEnum::BASIC_CONFIG);

        if(is_null($basicConfig) || empty($basicConfig[BasicConfigEnum::BASIC_SITE]) ){
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,"请配置正常的后台域名地址");
        }

        $model = new WxPayRequest(parent::getMiniAppOpenId(),"会员充值");
        $model->total_fee = Yii::$app->request->post('amount')*100 ;
        $model->notify_url=$basicConfig[BasicConfigEnum::BASIC_SITE].'api/mobile/user-charges/wx-pay-notify';
        $model->out_trade_no = StringHelper::uuid();
        $model->attach=parent::getUserId();
        $array = $model->toArray();

            $prePayId = $this->wxPayService->getPrePayId($array);

            if ($prePayId) {
                $result = $this->wxPayService->getJsSdk($prePayId);
                return $result;
            }

    }
    /**
     * 微信支付回调通知
     * @param request
     * @return
     */
    public function actionWxPayNotify()
    {
        //获取返回的xml
        $testxml = file_get_contents("php://input");
        //将xml转化为json格式
        $jsonxml = json_encode(simplexml_load_string($testxml, 'SimpleXMLElement', LIBXML_NOCDATA));
        //转成数组
        $data = json_decode($jsonxml, true);
        //保存微信服务器返回的签名sign
        $data_sign = $data['sign'];
        $sign = $this->wxPayService->makeSign($data);
        //判断签名是否正确,判断支付状态
        if (($sign === $data_sign) && ($data['return_code'] == 'SUCCESS') && ($data['result_code'] == 'SUCCESS')) {
            $results = $data;
            //获取服务器返回的数据
            $order_sn = $data['out_trade_no'];    //订单号
            $attach = $data['attach'];        //附加参数,选择传递订单ID
            $openid = $data['openid'];          //付款人openID
            $total_fee = (int)$data['total_fee']   ;    //付款金额

            $model =new  UserWalletDetailModel();
            $model->id=$order_sn;
            $model->user_id=$attach;
            $model->operator=$openid;
            $model->type=WalletStatusEnum::CHARGE;
            $model->is_deduct=StatusEnum::COME_IN;
            $model->amount= number_format($total_fee/100,2);

            $this->walletDetailService->addUserWalletDetail($model);

        } else {
            $results = false;
        }
        //返回状态给微信服务器
        if ($results) {
            $str = WxNotifyEnum::SUCCESS;
        } else {
            $str = WxNotifyEnum::FAIL;
        }
        return $str;

    }

    /**
     * 测试用例
     */
    public function actionTestJob()
    {
        $model =new  UserWalletDetailModel();
        $model->user_id='QhHS0t91CcaZ4wQOftzwH_QMipV8LAKM';
        $model->open_id='15648978';
        $model->id='2.22';
        $model->type=WalletStatusEnum::CHARGE;
        $model->is_deduct=StatusEnum::COME_IN;
        $model->amount= 0.01;

       return $this->walletDetailService->addUserWalletDetail($model);
    }
}
/**********************End Of UserInfo 控制层************************************/



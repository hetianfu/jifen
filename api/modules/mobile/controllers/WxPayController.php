<?php
namespace api\modules\mobile\controllers;

use api\modules\auth\ApiAuth;
use api\modules\mobile\service\WxPayService;
use yii;

/**
 * 订单支付
 *  @author  Round
 *  @E-mail: Administrator@qq.com
 */
class WxPayController extends BaseController
{

    private $service;
    public function init()
    {
        parent::init();

        $this->service=new WxPayService();
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => ApiAuth::class,
            'optional' => ['set-sub-merchant','get-pay-config','create-pay-qr-code','unify-pay-order','get-js-sdk']
        ];
        return $behaviors;
    }
/*********************UserInfo模块控制层************************************/ 
	/**
	 * 设置子商户信息
     * @return mixed
     */
	public function actionSetSubMerchant()
	{
        return $this->service->setSubMerchant("1547668191");

	}

    public function actionGetJsSdk()
    {   $prepayId="wx061623483800513f80a269751595935500";
        return $this->service->getJsSdk($prepayId);

    }

    public function actionGetPayConfig()
    {
        return $this->service->getPayConfig();

    }
    public function actionFreshCardPay()
    {
        $array=[
            'body' => 'image形象店-深圳腾大- QQ公仔',
            'out_trade_no' => '1217752501201407033233368018',
            'total_fee' => 888,
            'auth_code' => '120061098828009406',
        ];
        return $this->service->freshCardPay($array);

    }
    public function actionCreatePayQrCode()
    {
        return $this->service->schemeCode("---------");

    }
    public function actionUnifyPayOrder()
    {   $id=date("Ymd-His",time()).'-'.rand(10000,100000);
        $array=[
            'body' => '会员充值',
            'out_trade_no' => $id,
            'total_fee' => 88,
           // 'spbill_create_ip' => '123.12.12.123', // 可选，如不传该参数，SDK 将会自动获取相应 IP 地址
            'notify_url' => 'https://pay.weixin.qq.com/wxpay/pay.action', // 支付结果通知网址，如果不设置则会使用配置里的默认地址
            'trade_type' => 'JSAPI', // 请对应换成你的支付方式对应的值类型
            //服务商模式下, 需使用 sub_openid, 并传入sub_mch_id 和sub_appid
            'sub_mch_id'=>'1547668191',
            'sub_openid' => 'o7vDT5IGX1ieZH7zk73rd-KPpCXI',
        ];
        return $this->service->unifyPayOrder($array);

    }
    public function actionGetPayOrderInfo()
    {
        return $this->service->getPayOrderInfo(Yii::$app->request->get('id'));

    }
    public function actionClosedPayOrder()
    {
        return $this->service->closedPayOrder(Yii::$app->request->get('id'));

    }

	}
/**********************End Of UserInfo 控制层************************************/ 



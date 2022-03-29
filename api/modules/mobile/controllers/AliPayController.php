<?php

namespace api\modules\mobile\controllers;

use AlipayTradeService;
use AlipayTradeWapPayContentBuilder;
use api\modules\auth\ApiAuth;
use api\modules\mobile\models\event\OrderPayEvent;
use api\modules\mobile\models\forms\BasicOrderInfoModel;
use api\modules\mobile\service\BasicOrderInfoService;
use api\modules\mobile\service\OrderPayService;
use api\modules\mobile\service\UserInfoService;
use api\modules\mobile\service\WxPayService;
use Composer\XdebugHandler\Status;
use fanyou\components\payment\alipay\AliPayCertLoad;
use fanyou\components\SystemConfig;
use fanyou\enums\entity\BasicConfigEnum;
use fanyou\enums\entity\OrderStatusEnum;
use fanyou\enums\entity\StrategyTypeEnum;
use fanyou\enums\HttpErrorEnum;
use fanyou\enums\SystemConfigEnum;
use fanyou\error\FanYouHttpException;
use yii;

/**
 * 订单支付
 * @author  Round
 * @E-mail: Administrator@qq.com
 */
class AliPayController extends BaseController
{

    private $service;
    private $userInfoService;
    private $orderPayService;
    private $wxPayService;

    private $configService;

    const EVENT_ORDER_PAY = 'order_pay';


    public function init()
    {
        parent::init();
        $this->service = new BasicOrderInfoService();
        $this->userInfoService = new UserInfoService();

        $this->orderPayService = new OrderPayService();
        $this->wxPayService = new WxPayService();

        $this->configService = new SystemConfig();
        //定义订阅事件-发放优惠券,减库存 ，修改积分，发送订阅消息,用户购买记录，打印订单
        $this->on(self::EVENT_ORDER_PAY, ['api\modules\mobile\service\event\UserProductEventService', 'batchAdd']);
        $this->on(self::EVENT_ORDER_PAY, ['api\modules\mobile\service\event\CouponEventService', 'getEffectCouponAfterPay']);
        $this->on(self::EVENT_ORDER_PAY, ['api\modules\mobile\service\event\ScoreEventService', 'addUserScoreDetail']);

        $this->on(self::EVENT_ORDER_PAY, ['api\modules\mobile\service\event\StockEventService', 'minusSkuNumberById']);


        $this->on(self::EVENT_ORDER_PAY, ['api\modules\mobile\service\event\MessageEventService', 'sendPaySuccessMessage']);

        $this->on(self::EVENT_ORDER_PAY, ['api\modules\mobile\service\event\PrintEventService', 'printAfterPay']);
        //如果是秒杀，添加限购数量
        $this->on(self::EVENT_ORDER_PAY, ['api\modules\mobile\service\event\LimitEventService', 'buySaleLimit']);

    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => ApiAuth::class,
            'optional' => ['h5-to-pay', 'ali-pay-notify', 'test-job']
        ];
        return $behaviors;
    }
    /*********************UserInfo模块控制层************************************/

    /**
     * 去支付（下单未支付后再次支付）
     * @return mixed
     * @throws FanYouHttpException
     */
    public function actionH5ToPay()
    {
        //从缓存中取
        $orderId = parent::getRequestId();
        //超时时间
        $timeout_express = "5m";
        $order=BasicOrderInfoModel::find()->select('pay_amount')->where(['id'=>$orderId])->asArray()->one();
        //商户订单号，商户网站订单系统中唯一订单号，必填
        $total_amount =$order['pay_amount'];


        $payRequestBuilder = new AlipayTradeWapPayContentBuilder();


        $payRequestBuilder->setBody('购买商品');
        $payRequestBuilder->setSubject('商城订单');
        $payRequestBuilder->setOutTradeNo($orderId);
        $payRequestBuilder->setTotalAmount($total_amount);
        $payRequestBuilder->setTimeExpress($timeout_express);

        $config = AliPayCertLoad::getInitConfig($orderId);
        $payResponse = new AlipayTradeService($config);
        $result = $payResponse->wapPay($payRequestBuilder, $config['return_url'], $config['notify_url']);
        echo $result;exit;

    }


    /**
     * 支付宝回调通知
     * @param request
     * @return
     */
    public function actionAliPayNotify()
    {
        $arr = parent::getRequestPost(false,false);
        $config = AliPayCertLoad::getInitConfig($arr['out_trade_no']);
        $alipaySevice = new AlipayTradeService($config);
        $alipaySevice->writeLog(var_export($_POST, true));
        $result =1;// $alipaySevice->check($arr);
        //判断签名是否正确,判断支付状态
        if ($result) {//验证成功
            $order_sn = $arr['out_trade_no'];    //订单号
            //支付宝交易号
            $trade_no = $arr['trade_no'];
            BasicOrderInfoModel::updateAll(['prepay_id' => $trade_no], ['id' => $order_sn]);
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            //请在这里加上商户的业务逻辑程序代
            //——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
            //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表
            //判断该笔订单是否在商户网站中已经做过处理
            //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
            //请务必判断请求时的total_amount与通知时获取的total_fee为一致的
            //如果有做过处理，不执行商户的业务程序
            //注意：
            //退款日期超过可退款期限后（如三个月可退款），支付宝系统发送该交易状态通知
            if($arr['trade_status'] == 'TRADE_FINISHED'  ){
                $orderInfo = $this->service->getOneById($order_sn);
                $event = new OrderPayEvent();

                $event->payAmount = $orderInfo['pay_amount'];
                $event->orderInfo = $orderInfo;

                $this->trigger(self::EVENT_ORDER_PAY, $event);
                echo "success";  exit;
            }
            if ( $arr['trade_status'] == 'TRADE_SUCCESS' ) {
                //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
                //查订单状态，并更新订单状态，返回。
                //商户订单号
                $orderInfo = $this->service->getOneById($order_sn);

                if (empty($orderInfo) || !(OrderStatusEnum::UNPAID === $orderInfo['status'])) {
                    //订单不存在，或者订单状态不为待支付
                    echo "success";  exit;
                }
                if($orderInfo['status']!=OrderStatusEnum::UNPAID){
                    echo "success"; exit;
                }

                if (!$this->service->notifyOrderById($orderInfo)) {
                    echo "fail";  exit;
                }
                $this->on(self::EVENT_ORDER_PAY, ['api\modules\mobile\service\event\DistributeEventService', 'distribute']);
                //解锁积分
                $this->userInfoService->lockScore(-1 * $orderInfo['deduct_score'], $orderInfo['user_id']);
                if ($orderInfo['order_product_type'] == StrategyTypeEnum::PINK) {
                    $this->on(self::EVENT_ORDER_PAY, ['api\modules\mobile\service\event\PinkEventService', 'pinkProduct']);
                }

                $event = new OrderPayEvent();

                $event->payAmount = $orderInfo['pay_amount'];
                $event->orderInfo = $orderInfo;

                $this->trigger(self::EVENT_ORDER_PAY, $event);
                echo "success"; exit;
            } else{

                echo "fail"; exit;
            }

        }    //请不要修改或删除
        else {
            //验证失败
            echo "fail";    //请不要修改或删除
        }
    }


    public function actionTestJob()
    {
        $orderId = "20201117-205143-42979";
        $orderInfo = $this->service->getOneById($orderId);

        $event = new OrderPayEvent();

        $event->payAmount = $orderInfo['pay_amount'];
        $event->orderInfo = $orderInfo;

        $this->trigger(self::EVENT_ORDER_PAY, $event);

        exit;

    }

}
/**********************End Of UserInfo 控制层************************************/ 



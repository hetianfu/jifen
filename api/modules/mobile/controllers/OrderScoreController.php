<?php

namespace api\modules\mobile\controllers;

use api\modules\auth\ApiAuth;
use api\modules\mobile\models\CalculateOrderInfoResult;
use api\modules\mobile\models\event\OrderPayEvent;
use api\modules\mobile\models\forms\BasicOrderInfoModel;
use api\modules\mobile\models\forms\OrderPayProductGroup;
use api\modules\mobile\models\forms\UserInfoModel;
use api\modules\mobile\models\forms\UserScoreDetailModel;
use api\modules\mobile\models\request\CalculateOrderInfoRequest;
use api\modules\mobile\service\BasicOrderInfoService;
use api\modules\mobile\service\CouponUserService;
use api\modules\mobile\service\OrderPayService;
use api\modules\mobile\service\ShopFreightService;
use api\modules\mobile\service\UserInfoService;
use api\modules\mobile\service\UserScoreDetailService;
use api\modules\mobile\service\WxPayService;
use fanyou\components\SystemConfig;
use fanyou\enums\CacheEnum;
use fanyou\enums\entity\OrderStatusEnum;
use fanyou\enums\HttpErrorEnum;
use fanyou\enums\NumberEnum;
use fanyou\enums\OrderPayEnum;
use fanyou\enums\PayStatusEnum;
use fanyou\enums\StatusEnum;
use fanyou\enums\WalletStatusEnum;
use fanyou\error\errorCode\ErrorOrder;
use fanyou\error\errorCode\ErrorUser;
use fanyou\error\FanYouHttpException;
use fanyou\tools\ArrayHelper;
use fanyou\tools\StringHelper;
use yii;
use yii\web\HttpException;
use yii\web\UnprocessableEntityHttpException;

/**
 * 购买积分商品
 * @author  Round
 * @E-mail: Administrator@qq.com
 */
class OrderScoreController extends BaseController
{

    private $service;
    private $userInfoService;
    private $scoreDetailService;
    private $orderPayService;
    private $userCouponService;
    private $wxPayService;

    private $freightService;
    private $userScoreService;

    private $configService;
    const EVENT_ORDER_PAY = 'order_pay';

    const EVENT_PRODUCT_STOCK= 'product_stock';
    public function init()
    {
        parent::init();
        $this->service = new BasicOrderInfoService();
        $this->userInfoService = new UserInfoService();
        $this->scoreDetailService = new UserScoreDetailService();
        $this->userCouponService = new CouponUserService();
        $this->orderPayService = new OrderPayService();
        $this->wxPayService = new WxPayService();

        $this->freightService = new ShopFreightService();

        $this->userScoreService = new UserScoreDetailService();

        $this->configService=new SystemConfig();

        //定义订阅事件-发放优惠券,减库存 ，修改积分，发送订阅消息,用户购买记录，打印订单
        $this->on(self::EVENT_ORDER_PAY, ['api\modules\mobile\service\event\UserProductEventService', 'batchAdd']);
        $this->on(self::EVENT_ORDER_PAY, ['api\modules\mobile\service\event\PrintEventService', 'printAfterPay']);

        $this->on(self::EVENT_PRODUCT_STOCK, ['api\modules\mobile\service\event\StockEventService', 'minusSkuNumberById']);

    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => ApiAuth::class,
            'optional' => [ 'roll-back-product-stock', 'test-job','calculate-order','submit-order']
        ];
        return $behaviors;
    }
    /*********************UserInfo模块控制层************************************/
    /**
     * 待支付时订单详情
     * @return mixed
     * @throws HttpException
     */
    public function actionGetPayOrderInfo()
    {
        $model = new UserInfoModel();
        $model->setAttributes($this->getRequestPost());
        if ($model->validate()) {
            return $this->service->addUserInfo($model);
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, parent::getModelError($model));
        }
    }
    /**
     * 下单之前计算订单详情
     * @return CalculateOrderInfoResult|array
     * @throws FanYouHttpException
     */
    public function actionCalculateOrder()
    {
        $request = new CalculateOrderInfoRequest();
        $request->setAttributes(parent::getRequestPost(true, false), false);

        $id = $request->id;
        if ( empty($request->productList)) {
            throw new  FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, ErrorOrder::NO_ORDER_SUBMIT);
        }

        if (!empty($id)) {
            $result = $this->othersCalculate($request, $id);
            $result->distribute= $request->distribute ;
        } else {
            $id = parent::createRandomOutTradeNo();
            $request->userId = parent:: getUserId();
            $isVip=empty(parent::getUserCacheByName('is_vip'))?0:1;
            $orderInfo = $this->firstCalculate($id, $request->userId, $request->productList,
                $isVip,
                $request->scoreDiscount );

            $orderInfo->distribute=$request->distribute;
            $result = $orderInfo->toArray();
        }
        $cacheKey = CacheEnum::getPrefix(OrderPayEnum::CALCULATE_ORDER . $id);
        parent::setCache($cacheKey, $result, OrderPayEnum::CALCULATE_ORDER_TIME);
        return $result;

    }

    /**
     * 积分支付
     * @param BasicOrderInfoModel $orderInfo
     * @return mixed
     * @throws FanYouHttpException
     */
    public function actionScoreToPay(BasicOrderInfoModel $orderInfo)
    {
        $userId = $orderInfo->user_id;
        $orderId =$orderInfo->id;

        $userInfo = $this->userInfoService->getOneById($userId);

        if (empty($userInfo) || empty($userInfo['total_score'])) {
            throw new  FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, ErrorUser::USER_WALLET_LESS);
        }
        $leftAmount = $userInfo['total_score'];

        if ($leftAmount < $orderInfo['pay_amount']) {
            throw new  FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, ErrorUser::USER_WALLET_LESS);
        }

        $wDetail = new UserScoreDetailModel();
        $wDetail->order_id = $orderId;
        $wDetail->is_deduct = StatusEnum::COME_OUT;
        $wDetail->score = $orderInfo['pay_amount'];
        $wDetail->user_id = $userId;
        $wDetail->type = WalletStatusEnum::CONSUME;

        if ($this->scoreDetailService->addUserScoreDetail($wDetail)) {

            $result = $this->service->notifyScoreOrderById($orderInfo);
            if ($result) {
                $event = new OrderPayEvent();
                $event->payAmount = $orderInfo['pay_amount'];
                $event->orderInfo = $orderInfo;
                $this->trigger(self::EVENT_ORDER_PAY, $event);
            }
            return $result;
        } else {
            throw new  FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, ErrorOrder::FAIL_SUBMIT_ORDER);
        }
    }

    /**
     * 提交订单
     * @return mixed
     * @throws FanYouHttpException
     * @throws UnprocessableEntityHttpException
     */
    public function actionSubmitOrder()
    {   //从缓存中取，不存在，则重新生成
        //需再次核验，优惠券--积分抵扣--
        //计算，下单，生成
        $submit = new CalculateOrderInfoRequest();
        $submit->setAttributes($this->getRequestPost(true, false), false);

        $calculateOrder = $this->verifyOrderSubmit($submit->id);
        if (empty($calculateOrder)) {
            throw new  FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, ErrorOrder::FAIL_SUBMIT_ORDER);
        }
        $model = $this->changeToOrderInfo($calculateOrder);

        $model->remark=$submit->remark;
        $model->pay_type = PayStatusEnum::SCORE;
        $userInfo = $this->userInfoService->getOneById($model->user_id);
        if($model->pay_type===PayStatusEnum::SCORE ){
            //验证钱包余额
            if($userInfo['total_score']<  $calculateOrder->payAmount){
                throw new  FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, ErrorUser::USER_WALLET_LESS);
            }
        }
        $model->status=$model->distribute? OrderStatusEnum::UN_CHECK:OrderStatusEnum::UN_SEND;
        $userNickName = $userInfo['nick_name'];
        $userTelephone = $userInfo['telephone'];
        $userHeadImg = $userInfo['head_img'];
        $userCode = $userInfo['code'];

        $model->user_snap = json_encode(['nick_name' => $userNickName, 'telephone' => $userTelephone, 'code' => $userCode,'head_img' => $userHeadImg],JSON_UNESCAPED_UNICODE);

        $model->search_word = $model->id . '|' . $userNickName . '|' . $userCode . '|' . $userTelephone;

        $model->cooperate_shop_id = $submit->cooperateShopId;

        !empty($submit->connectSnap)&& $model->connect_snap= json_encode($submit->connectSnap,JSON_UNESCAPED_UNICODE);

        !empty($submit->addressSnap)&&$model->address_snap =json_encode($submit->addressSnap,JSON_UNESCAPED_UNICODE);
        !empty($submit->cooperateShopAddress)&& $model->cooperate_shop_address_snap=json_encode($submit->cooperateShopAddress,JSON_UNESCAPED_UNICODE);

        if ($model->validate()) {

            //减库存
            $event = new OrderPayEvent();
            $event->orderInfo = $model;
            $this->trigger(self::EVENT_PRODUCT_STOCK, $event);

            $result = $this->service->addBasicOrderInfo($model);
            $model->id=$result;
            $this->actionScoreToPay($model);
            return $result;

        } else {
            throw new  UnprocessableEntityHttpException(parent::getModelError($model));
        }
    }


    /**
     * 提醒发货
     * @return mixed
     * @throws UnprocessableEntityHttpException
     */
    public function actionDoReminder()
    {
        $model = new UserInfoModel();
        $model->setAttributes($this->getRequestPost());
        if ($model->validate()) {
            return $this->service->addUserInfo($model);
        } else {
            throw new  UnprocessableEntityHttpException(parent::getModelError($model));
        }
    }

    /**
     * 首次计算订单金额
     * @param $id
     * @param $userId
     * @param $productList
     * @param $isVip
     * @return CalculateOrderInfoResult
     */
    private function firstCalculate($id, $userId, $productList,$isVip ): CalculateOrderInfoResult
    {
        $orderInfo = new CalculateOrderInfoResult();
        $orderInfo->id = $id;
        $orderInfo->userId = $userId;

        // 将商品分组计算价格
        $group = $this->orderPayService->groupOrderPayProduct($productList, $isVip);

        $groupOrderPayProduct = new OrderPayProductGroup();
        $groupOrderPayProduct->setAttributes($group);

        $orderInfo->cartIds = $groupOrderPayProduct->cartIds;

        //获取当前商品列表
        $orderInfo->productList = $groupOrderPayProduct->productList;

        //获取当前订单原价
        $orderInfo->originAmount = $groupOrderPayProduct->originAmount;
        //获取当前商品价格
        $orderInfo->productAmount = $groupOrderPayProduct->productAmount;
        //计算订单应支付金额
        $payAmount = $groupOrderPayProduct->productAmount;
        //获取当前订单邮费
        $orderInfo->freightAmount = $this->calculateFreightAmount($payAmount, $orderInfo->distribute);

        $orderInfo->payAmount =$payAmount + $orderInfo->freightAmount;
        $orderInfo->amount = round($payAmount, 2);//  number_format($payAmount, 2,'.','');
        // 订单折扣
        $orderInfo->discountAmount =$groupOrderPayProduct->discountAmount;// $orderInfo->scoreAmount;//   $groupOrderPayProduct->discountAmount;

        $orderInfo->productScore = $groupOrderPayProduct->productScore;
        $orderInfo->merchantId = $groupOrderPayProduct->merchantId;
        $orderInfo->isVip=$isVip;

        return $orderInfo;
    }

    /**
     * 非首次计算订单金额
     * @param CalculateOrderInfoRequest $request`
     * @param $id
     * @return CalculateOrderInfoResult
     * @throws FanYouHttpException
     */
    private function othersCalculate(CalculateOrderInfoRequest $request, $id): CalculateOrderInfoResult
    {
        $result = new CalculateOrderInfoResult();
        $cacheKey = CacheEnum::getPrefix(OrderPayEnum::CALCULATE_ORDER . $id);
        $orderInfo = parent::getCache($cacheKey);
        if (empty($orderInfo)) {
            throw new  FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, ErrorOrder::PAGE_OUT_TIME);
        }
        $distribute = $request->distribute;

        $userCouponId = $request->userCouponId;
        $result->setAttributes(ArrayHelper::toArray(json_decode($orderInfo)), false);
        $result->userCouponId=$userCouponId;
        $amount = $result->amount * 1;

        $result->freightAmount = $this->calculateFreightAmount($amount, $distribute);

        //优惠券Id ,是否有效，
        $result->payAmount =  $result->amount +   $result->freightAmount;

        $result->scoreAmount =0;
        $result->deductScore= 0;

        return $result;
    }
     /**
     * 订单提交较验
     * 抵扣用户积分
     * 失能用户卡券
     * @param $id
     * @return CalculateOrderInfoResult
     * @throws FanYouHttpException
     */
    private function verifyOrderSubmit($id): CalculateOrderInfoResult
    {
        $cacheKey = CacheEnum::getPrefix(OrderPayEnum::CALCULATE_ORDER . $id);
        $orderInfo = parent::getCache($cacheKey);
        if (empty($orderInfo)) {
            throw new  FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, ErrorOrder::PAGE_OUT_TIME);
        }
        $result = new CalculateOrderInfoResult();
        $result->setAttributes(ArrayHelper::toArray(json_decode($orderInfo)), false);

        $checkResult = $this->orderPayService->checkStock($result->productList);

        if (!$checkResult) {
            throw new  FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, $checkResult);
        }

        return $result;
    }

    /**
     * 将计算的订单数据转换为数据库订单
     * @param CalculateOrderInfoResult $calculate
     * @return BasicOrderInfoModel
     */
    private function changeToOrderInfo(CalculateOrderInfoResult $calculate): BasicOrderInfoModel
    {

        $model = new BasicOrderInfoModel();

        $model->setAttributes(StringHelper::toUnCamelize($calculate->toArray()), false);

        $model->short_order_no= $this->service->countToday()+NumberEnum::ONE;

        $model->cart_snap = json_encode($calculate->productList,JSON_UNESCAPED_UNICODE);

        $model->un_paid_seconds = time() + Yii::$app->params['orderRemainTime'];

        $model->type=OrderStatusEnum::SCORE_TYPE;
        return $model;
    }

    /**
     * 计算邮费
     * @param $amount
     * @param int $distribute
     * @return float|int
     */
    private function calculateFreightAmount($amount, $distribute = 0): float
    {
        return NumberEnum::ZERO;//round($this->freightService->getFreightAmount($amount, $distribute), 2);
    }

}
/**********************End Of UserInfo 控制层************************************/ 



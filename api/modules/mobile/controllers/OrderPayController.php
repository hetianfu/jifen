<?php

namespace api\modules\mobile\controllers;

use api\modules\auth\ApiAuth;
use api\modules\mobile\models\CalculateOrderInfoResult;
use api\modules\mobile\models\event\OrderPayEvent;
use api\modules\mobile\models\forms\BasicOrderInfoModel;
use api\modules\mobile\models\forms\CouponUserModel;
use api\modules\mobile\models\forms\OrderPayProductGroup;
use api\modules\mobile\models\forms\ProxyPayModel;
use api\modules\mobile\models\forms\UserInfoModel;
use api\modules\mobile\models\forms\UserWalletDetailModel;
use api\modules\mobile\models\request\CalculateOrderInfoRequest;
use api\modules\mobile\models\UserInfoResult;
use api\modules\mobile\service\BasicOrderInfoService;
use api\modules\mobile\service\CouponUserService;
use api\modules\mobile\service\event\MsgEventService;
use api\modules\mobile\service\LoginService;
use api\modules\mobile\service\OrderPayService;
use api\modules\mobile\service\PinkService;
use api\modules\mobile\service\ProxyPayService;
use api\modules\mobile\service\ShopFreightService;
use api\modules\mobile\service\UserInfoService;
use api\modules\mobile\service\UserScoreDetailService;
use api\modules\mobile\service\UserShopCartService;
use api\modules\mobile\service\UserWalletDetailService;
use api\modules\mobile\service\WxPayService;
use api\modules\seller\models\forms\ChannelModel;
use fanyou\components\SystemConfig;
use fanyou\enums\CacheEnum;
use fanyou\enums\entity\BasicConfigEnum;
use fanyou\enums\entity\OrderStatusEnum;
use fanyou\enums\entity\StrategyTypeEnum;
use fanyou\enums\HttpErrorEnum;
use fanyou\enums\NumberEnum;
use fanyou\enums\OrderPayEnum;
use fanyou\enums\PayStatusEnum;
use fanyou\enums\StatusEnum;
use fanyou\enums\SystemConfigEnum;
use fanyou\enums\WalletStatusEnum;
use fanyou\enums\WxNotifyEnum;
use fanyou\error\errorCode\ErrorOrder;
use fanyou\error\errorCode\ErrorProduct;
use fanyou\error\errorCode\ErrorUser;
use fanyou\error\FanYouHttpException;
use fanyou\service\FanYouSystemGroupService;
use fanyou\service\ThirdSmsService;
use fanyou\tools\ArrayHelper;
use fanyou\tools\StringHelper;
use yii;
use yii\web\HttpException;
use yii\web\UnprocessableEntityHttpException;
use function AlibabaCloud\Client\json;

/**
 * ????????????
 * @author  Round
 * @E-mail: Administrator@qq.com
 */
class OrderPayController extends BaseController
{

  private $service;
  private $userInfoService;
  private $walletDetailService;
  private $orderPayService;
  private $userCouponService;
  private $wxPayService;

  private $freightService;
  private $couponUserService;
  private $userScoreService;
  private $shopCartService;

  private $configService;

  private $pinkService;

  private $loginService;
  private $proxyPayService;
  const EVENT_ORDER_PAY = 'order_pay';

  const EVENT_PRODUCT_STOCK = 'product_stock';

  // const EVENT_PRODUCT_STOCK_ROLLBACK= 'product_stock_rollback';
  public function init()
  {
    parent::init();
    $this->service = new BasicOrderInfoService();
    $this->userInfoService = new UserInfoService();
    $this->walletDetailService = new UserWalletDetailService();
    $this->userCouponService = new CouponUserService();
    $this->orderPayService = new OrderPayService();
    $this->wxPayService = new WxPayService();

    $this->freightService = new ShopFreightService();

    $this->couponUserService = new  CouponUserService();
    $this->userScoreService = new UserScoreDetailService();
    $this->shopCartService = new UserShopCartService();

    $this->configService = new SystemConfig();

    $this->pinkService = new PinkService();
    $this->proxyPayService = new ProxyPayService();

    $this->loginService = new LoginService();

    //??????????????????-???????????????,????????? ????????????????????????????????????,?????????????????????????????????
    $this->on(self::EVENT_ORDER_PAY, ['api\modules\mobile\service\event\CouponEventService', 'getEffectCouponAfterPay']);
    $this->on(self::EVENT_ORDER_PAY, ['api\modules\mobile\service\event\ScoreEventService', 'addUserScoreDetail']);
    $this->on(self::EVENT_ORDER_PAY, ['api\modules\mobile\service\event\UserProductEventService', 'batchAdd']);
    $this->on(self::EVENT_ORDER_PAY, ['api\modules\mobile\service\event\PrintEventService', 'printAfterPay']);
    //????????????????????????????????????
    $this->on(self::EVENT_ORDER_PAY, ['api\modules\mobile\service\event\LimitEventService', 'buySaleLimit']);


    $this->on(self::EVENT_ORDER_PAY, ['api\modules\mobile\service\event\StockEventService', 'minusSkuNumberById']);

    $this->on(self::EVENT_ORDER_PAY, ['api\modules\mobile\service\event\MessageEventService', 'sendPaySuccessMessage']);
    //$this->on(self::EVENT_ORDER_PAY, ['api\modules\mobile\service\event\MsgEventService', 'actionSendRegMsg']);


  }

  public function behaviors()
  {
    $behaviors = parent::behaviors();
    $behaviors['authenticator'] = [
      'class' => ApiAuth::class,
      'optional' => ['wx-pay-notify', 'roll-back-product-stock', 'test-job', 'calculate-order', 'submit-order']
    ];
    return $behaviors;
  }
  /*********************UserInfo???????????????************************************/
  /**
   * ????????????????????????
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
   * ???????????????????????????????????????
   * @return mixed
   * @throws FanYouHttpException
   */
  public function actionGetEffectCoupon()
  {
    $userId = Yii::$app->request->post("userId");
    $productList = Yii::$app->request->post("productList");
    if (empty($productList)) {
      throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, ErrorProduct::EMPTY_PRODUCT);
    }
    //????????????????????????????????????
    $couponList = $this->userCouponService->getCanUsedCouponList($userId);
    // ???????????????????????????
    $isVip = empty(parent::getUserCacheByName('is_vip')) ? 0 : 1;
    $group = $this->orderPayService->groupOrderPayProduct($productList, $isVip);
    //???????????????????????????????????????
    $couponList = $this->orderPayService->getCanUseCoupon($couponList, $group);
    return $couponList;
  }

  /**
   * ??????????????????????????????
   * @return CalculateOrderInfoResult|array
   * @throws FanYouHttpException
   */
  public function actionCalculateOrder()
  {
    $request = new CalculateOrderInfoRequest();
    $request->setAttributes(parent::getRequestPost(true, false), false);

   // $sourceId =  $request->sourceId;
    $sourceId = Yii::$app->session->get('sourceId');

    $ChannelModel =   ChannelModel::find()->where(['code'=>$sourceId])->one();
    if ($ChannelModel){
      $sourceId = $ChannelModel->id;
    }else{
      $ChannelModel = UserInfoModel::find()->one();
      $sourceId = $ChannelModel->id;
    }

    $id = $request->id;
    if (empty($request->productList)) {
      throw new  FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, ErrorOrder::NO_ORDER_SUBMIT);
    }

    if (!empty($id)) {
      $result = $this->othersCalculate($request, $id);
      $result->distribute = $request->distribute;
    } else {
      $id = parent::createRandomOutTradeNo();
      $request->userId = parent:: getUserId();
      $isVip = empty(parent::getUserCacheByName('is_vip')) ? 0 : 1;
      $orderInfo = $this->firstCalculate($id, $request->userId, $request->productList,
        $isVip, $request->scoreDiscount, $request->cityCode, $request->fullPay);

      $orderInfo->distribute = $request->distribute;
      $result = $orderInfo->toArray();
    }
    if ($result['payAmount'] < 0) {
      $result->payAmount = 0;
    }


    $cacheKey = CacheEnum::getPrefix(OrderPayEnum::CALCULATE_ORDER . $id);
    $remainTime = empty($_ENV['ORDER_REMAIN_TIME']) ? 3600 : $_ENV['ORDER_REMAIN_TIME'];
    parent::setCache($cacheKey, $result, $remainTime + OrderPayEnum::CALCULATE_ORDER_TIME);

    //?????????????????? ???????????????
    $channel = ChannelModel::findOne($sourceId);
    if($channel){
      $place_order_sum = $channel->place_order_sum;
      $place_order = $channel->place_order;
      $channel->place_order = $place_order+1;
      $channel->place_order_sum = $place_order_sum+$result['payAmount'];
      $channel->save(false);
    }
    return $result;

  }

  /**
   * ??????????????????
   */
  public function actionGetWallet()
  {
    return $this->userInfoService->getAmountById(parent::getUserId());
  }


  /**
   * ????????????
   * @return mixed
   * @throws FanYouHttpException
   */
  public function actionWalletToPay()
  {
    $userId = parent::getUserId();
    $orderId = parent::getRequestId();

    $userInfo = $this->userInfoService->getOneById($userId);

    if (empty($userInfo) || empty($userInfo['amount'])) {
      throw new  FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, ErrorUser::USER_WALLET_LESS);
    }
    $leftAmount = $userInfo['amount'];
    $orderInfo = $this->service->getOneById($orderId);
    if ($leftAmount < $orderInfo['pay_amount']) {
      throw new  FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, ErrorUser::USER_WALLET_LESS);
    }
    if ($orderInfo['order_product_type'] == StrategyTypeEnum::PINK) {
      $this->on(self::EVENT_ORDER_PAY, ['api\modules\mobile\service\event\PinkEventService', 'pinkProduct']);
    }
    $wDetail = new UserWalletDetailModel();
    $wDetail->id = $orderId;
    $wDetail->is_deduct = StatusEnum::COME_OUT;
    $wDetail->amount = $orderInfo['pay_amount'];
    $wDetail->user_id = $userId;
    $wDetail->type = WalletStatusEnum::CONSUME;

    $wDetail->real_name = parent::getUserCacheByName('nick_name');

    if ($this->walletDetailService->addUserWalletDetail($wDetail)) {

      $result = $this->service->notifyOrderById($orderInfo);
      if ($result) {
        $event = new OrderPayEvent();
        $event->payAmount = $orderInfo['pay_amount'];
        $orderInfo['paid_time'] = time();
        $event->orderInfo = $orderInfo;
        $this->trigger(self::EVENT_ORDER_PAY, $event);
      }
      return $result;
    } else {
      throw new  FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, ErrorOrder::FAIL_SUBMIT_ORDER);
    }
  }


  /**
   * ????????????
   * @return mixed
   * @throws FanYouHttpException
   */
  public function actionAfterToPay()
  {
    $userId = parent::getUserId();
    $orderId = parent::getRequestId();
    $orderInfo = $this->service->getOneById($orderId);
    if ($orderInfo['order_product_type'] == StrategyTypeEnum::PINK) {
      $this->on(self::EVENT_ORDER_PAY, ['api\modules\mobile\service\event\PinkEventService', 'pinkProduct']);
    }
    $result = $this->service->notifyOrderById($orderInfo);
    if ($result) {
      $event = new OrderPayEvent();
      $event->payAmount = $orderInfo['pay_amount'];
      $orderInfo['paid_time'] = time();
      $event->orderInfo = $orderInfo;
      $this->trigger(self::EVENT_ORDER_PAY, $event);
    }
    return $result;

  }

  /**
   * ????????????
   * @return mixed
   * @throws FanYouHttpException
   * @throws UnprocessableEntityHttpException
   */
  public function actionSubmitOrder()
  {
    //?????????????????????????????????????????????
    //???????????????????????????--????????????--
    //????????????????????????
    $userId = parent::getUserId();



    $submit = new CalculateOrderInfoRequest();

    $submit->setAttributes($this->getRequestPost(false, false), false);

    $sourceId = Yii::$app->session->get('sourceId');
    $ChannelModel =   ChannelModel::find()->where(['code'=>$sourceId])->one();

    if ($ChannelModel){
      $submit->sourceId = $ChannelModel->id;
    }else{
      $ChannelModel = ChannelModel::find()->one();
      $submit->sourceId = $ChannelModel->id;
    }


    if (BasicOrderInfoModel::find()->where(['id' => $submit->id])->count()) {
      throw new  FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, '??????????????????');
    }

    $calculateOrder = $this->verifyOrderSubmit($submit->id, $submit->isPink, $submit->pinkId);
    $needScore = isset($calculateOrder->needScore) ? $calculateOrder->needScore : 0;
    if (empty($calculateOrder)) {
      throw new  FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, ErrorOrder::FAIL_SUBMIT_ORDER);
    }
    $model = $this->changeToOrderInfo($calculateOrder);
    $model->source_id = $submit->sourceId;


    if (empty($submit->payType)) {
      $submit->payType = PayStatusEnum::WX;
    }
    $model->remark = $submit->remark;
    $model->pay_type = $submit->payType;
    $userInfo = $this->userInfoService->getOneById($userId);
    if ($model->pay_type === PayStatusEnum::WALLET) {
      //??????????????????
      if ($userInfo['amount'] < $calculateOrder->payAmount) {
        throw new  FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, ErrorUser::USER_WALLET_LESS);
      }
    }

    $userTelephone = $userInfo['telephone'];
    if (empty($userTelephone)) {
      //?????????
      //?????????????????????????????????????????????????????????????????????
      //??????????????????????????????????????????????????????????????????
      //???????????????????????????????????????
      $userTelephone = $submit->addressSnap['telephone'];
      $existUser = UserInfoModel::findOne(['telephone' => $userTelephone]);
      if (empty($existUser)) {
        UserInfoModel::updateAll(['nick_name' => $submit->addressSnap['name'], 'telephone' => $userTelephone], ['id' => $userId]);
      } else {
        //???????????????
        $model->user_id = $existUser->id;
        $userInfo = $existUser;
        unset($userInfo->source_json);
        if (!empty($userInfo)) {
          $token = Yii::$app->request->getHeaders()['access-token'];
          $this->loginService->resetAuthKey($token, $userInfo, NumberEnum::TEN_DAYS);
        }

      }
    }
    $userNickName = $userInfo['nick_name'];
    $userHeadImg = $userInfo['head_img'];
    $userCode = $userInfo['code'];

    $model->appoint_time = strtotime($submit->appointTime);

    $model->cooperate_shop_id = $submit->cooperateShopId;

    !empty($submit->connectSnap) && $model->connect_snap = json_encode($submit->connectSnap, JSON_UNESCAPED_UNICODE);

    if (!empty($submit->addressSnap)) {
      $model->address_snap = json_encode($submit->addressSnap, JSON_UNESCAPED_UNICODE);
    }
    $model->phone_mode = $submit->phoneMode;
    //???????????????????????????
    $model->user_snap = json_encode(['nick_name' => $userNickName, 'telephone' => $userTelephone, 'code' => $userCode, 'head_img' => $userHeadImg], JSON_UNESCAPED_UNICODE);

    $model->search_word = $model->id . '|' . $userNickName . '|' . $userCode . '|' . $userTelephone;


    !empty($submit->cooperateShopAddress) && $model->cooperate_shop_address_snap = json_encode($submit->cooperateShopAddress, JSON_UNESCAPED_UNICODE);
    !empty($submit->isPink) && $model->type = OrderStatusEnum::PINK_TYPE;
    if ($model->validate()) {

      //?????????
//            $event = new OrderPayEvent();
//            $event->orderInfo = $model;
//            $this->trigger(self::EVENT_PRODUCT_STOCK, $event);


      $model->deduct_score = $model->deduct_score + $needScore;

//            $model->source_id = parent::getUserCacheByName('source_id');
      $model->source_id = $submit->sourceId;


      $result = $this->service->addBasicOrderInfo($model);
      if ($result) {
        //????????????
        $this->userInfoService->lockScore($model->deduct_score, $model->user_id);

        // ????????????
        $carIds = $calculateOrder->cartIds;
        $this->shopCartService->deleteByIds($carIds);
        //????????????
        if (!empty($model->user_coupon_id)) {
          $userCoupon = new CouponUserModel();
          $userCoupon->id = $model->user_coupon_id;
          $userCoupon->status = StatusEnum::USED;
          $this->userCouponService->updateCouponUserById($userCoupon);

        }
      }
      return $result;

    } else {
      throw new  UnprocessableEntityHttpException(parent::getModelError($model));
    }
  }

  /**
   * ?????????????????????????????????????????????
   * @return mixed
   * @throws FanYouHttpException
   */
  public function actionGoToPay()
  {
    //???????????????
    $orderId = parent::getRequestId();
    $miniAppId = parent::getMiniAppOpenId();
    $proxyPay =
      ProxyPayModel::find()->where(['order_id' => $orderId, 'mini_app_id' => $miniAppId])->orderBy(['created_at' => SORT_DESC])->asArray()->one();
    //    $this->proxyPayService->getByOrderIdAndMiniAppId($orderId, $miniAppId);
    if (!empty($proxyPay)) {
      return json_decode($proxyPay['detail']);
    }

    $array = $this->orderPayService->getWxPayRequest($orderId, $miniAppId);
    if (!empty($array)) {

      $basicConfig = $this->configService->getConfigInfoValue(false, SystemConfigEnum::BASIC_CONFIG);
      if (isset($basicConfig[BasicConfigEnum::BASIC_SITE])) {
        $array['notify_url'] = $basicConfig[BasicConfigEnum::BASIC_SITE] . '/api/mobile/order-pays/wx-pay-notify';
      }
      $unifyPay = $this->wxPayService->unifyPayOrder($array);
      $prePayId = $unifyPay['prepay_id'];

      if ($prePayId) {
        $updateOrder = new BasicOrderInfoModel();
        $updateOrder->id = $orderId;
        $updateOrder->prepay_id = $prePayId;
        $updateOrder->paid_user_id = parent::getUserId();
        $this->service->updateBasicOrderInfoById($updateOrder);
        $result = $this->wxPayService->getJsSdk($prePayId);
        $proxyPay = new ProxyPayModel();
        $proxyPay->id = $array['out_trade_no'];
        $proxyPay->order_id = $orderId;
        $proxyPay->user_id = parent::getUserId();
        $proxyPay->mini_app_id = $miniAppId;
        $proxyPay->prepay_id = $prePayId;
        $proxyPay->detail = json_encode($result, JSON_UNESCAPED_UNICODE);
        $this->proxyPayService->addProxyPay($proxyPay);
        return $result;
      }

    }
    throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, ErrorOrder::ORDER_PAY_OUT_TIME);
  }


  /**
   * ?????????????????????
   * @return mixed
   * @throws FanYouHttpException
   */
  public function actionGoToPayInMp()
  {
    //???????????????
    $orderId = parent::getRequestId();
    $mpAppId = parent::getMpOpenId();

    $proxyPay =
      ProxyPayModel::find()->where(['order_id' => $orderId, 'mini_app_id' => $mpAppId])->orderBy(['created_at' => SORT_DESC])->asArray()->one();
    //    $this->proxyPayService->getByOrderIdAndMiniAppId($orderId,$mpAppId);
    if (!empty($proxyPay)) {
      return json_decode($proxyPay['detail']);
    }
    $array = $this->orderPayService->getWxPayRequest($orderId, $mpAppId);

    if (!empty($array)) {

      $basicConfig = $this->configService->getConfigInfoValue(false, SystemConfigEnum::BASIC_CONFIG);
      if (isset($basicConfig[BasicConfigEnum::BASIC_SITE])) {
        $array['notify_url'] = $basicConfig[BasicConfigEnum::BASIC_SITE] . '/api/mobile/order-pays/wx-pay-notify';
      }

      $prePayId = $this->wxPayService->getPrePayId($array);
      if ($prePayId) {
        $updateOrder = new BasicOrderInfoModel();
        $updateOrder->id = $orderId;
        $updateOrder->pay_type = PayStatusEnum::WX_MP;
        $updateOrder->prepay_id = $prePayId;
        $updateOrder->paid_user_id = parent::getUserId();
        $this->service->updateBasicOrderInfoById($updateOrder);

        $result = $this->wxPayService->getJsSdk($prePayId);

        $proxyPay = new ProxyPayModel();
        $proxyPay->id = $array['out_trade_no'];
        $proxyPay->order_id = $orderId;
        $proxyPay->user_id = parent::getUserId();
        $proxyPay->mini_app_id = $mpAppId;
        $proxyPay->prepay_id = $prePayId;
        $proxyPay->detail = json_encode($result, JSON_UNESCAPED_UNICODE);
        $this->proxyPayService->addProxyPay($proxyPay);
        return $result;
      }
    }
    throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, "?????????????????????");
  }


  /**
   * ?????????????????????????????????????????????
   * @return mixed
   * @throws FanYouHttpException
   */
  public function actionH5ToPay()
  {
    //???????????????
    $orderId = parent::getRequestId();
    $miniAppId = parent::getUserId();
    //5??????????????????????????????????????????????????????
//        $proxyPay = $this->proxyPayService->getByOrderIdAndMiniAppId($orderId, $miniAppId);
//        //H5??????????????? 5???????????????
//        if (!empty($proxyPay) && ($proxyPay['created_at'] > (time() - 300))) {
//            return $proxyPay['detail'];
//        } elseif (!empty($proxyPay) &&  ($proxyPay['created_at'] < (time() - 300))) {
//            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, ErrorOrder::ORDER_PAY_OUT_TIME);
//        };
    //????????????????????????
    $array = $this->orderPayService->getWxPayRequest($orderId, null, 'MWEB');

    if (!empty($array)) {
      $basicConfig = $this->configService->getConfigInfoValue(false, SystemConfigEnum::BASIC_CONFIG);
      if (isset($basicConfig[BasicConfigEnum::BASIC_SITE])) {
        $array['notify_url'] = $basicConfig[BasicConfigEnum::BASIC_SITE] . '/api/mobile/order-pays/wx-pay-notify';
      }
      unset($array['openid']);
      unset($array['attach']);
      $unifyPay = $this->wxPayService->unifyPayOrder($array);
      $prePayId = $unifyPay['prepay_id'];
      $mwebUrl = $unifyPay['mweb_url'];

      $mwebUrl .= '&redirect_url=' . urlencode(FanYouSystemGroupService::getDm()) . 'pages/order/pay-text/index?id=' . $orderId;

      if ($prePayId) {
        $updateOrder = new BasicOrderInfoModel();
        $updateOrder->id = $orderId;
        $updateOrder->prepay_id = $prePayId;
        $updateOrder->paid_user_id = parent::getUserId();
        $this->service->updateBasicOrderInfoById($updateOrder);

        $proxyPay = new ProxyPayModel();
        $proxyPay->id = $array['out_trade_no'];
        $proxyPay->order_id = $orderId;
        $proxyPay->user_id = parent::getUserId();
        $proxyPay->mini_app_id = $miniAppId;
        $proxyPay->prepay_id = $prePayId;
        $proxyPay->detail = $mwebUrl;

        $this->proxyPayService->addProxyPay($proxyPay);
        return $mwebUrl;
      }
    }
    throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, '??????????????????');
  }

  /**
   * ????????????
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
   * ????????????????????????
   * @param $id
   * @param $userId
   * @param $productList
   * @param $isVip
   * @return CalculateOrderInfoResult
   */
  private function firstCalculate($id, $userId, $productList, $isVip, $scoreDiscount = 0, $cityCode = 0, $fullPay = 0): CalculateOrderInfoResult
  {
    $orderInfo = new CalculateOrderInfoResult();
    $orderInfo->id = $id;
    $orderInfo->userId = $userId;

    //????????????????????????????????????
    $couponList = $this->userCouponService->getCanUsedCouponList($userId);
    // ???????????????????????????
    $group = $this->orderPayService->groupOrderPayProduct($productList, $isVip, $cityCode, $fullPay);

    $groupOrderPayProduct = new OrderPayProductGroup();
    $groupOrderPayProduct->setAttributes($group, false);

    $orderInfo->cartIds = $groupOrderPayProduct->cartIds;
    $orderInfo->payType=$groupOrderPayProduct->payType;

    //????????????????????????
    $orderInfo->productList = $groupOrderPayProduct->productList;
    //???????????????????????????????????????
    $orderInfo->couponList = $this->orderPayService->getCanUseCoupon($couponList, $groupOrderPayProduct);
    //print_r($orderInfo->couponList);exit;
    //????????????????????????
    $orderInfo->originAmount = $groupOrderPayProduct->originAmount;
    //????????????????????????
    $orderInfo->productAmount = empty($fullPay) ? $groupOrderPayProduct->productAmount : $groupOrderPayProduct->originAmount;
    //???????????????????????????
    $payAmount = $groupOrderPayProduct->productAmount;
    $orderInfo->isVirtual = $groupOrderPayProduct->isVirtual;
    if (empty($orderInfo->isVirtual)) {
      $orderInfo->productFreight = $groupOrderPayProduct->productFreight;
      //????????????????????????
      $orderInfo->freightAmount = $this->calculateFreightAmount($payAmount, $orderInfo->distribute, $orderInfo->productFreight);
    }
    //?????????????????????????????????????????????
    // if (!empty($scoreDiscount)){
    $orderInfo->deductScoreAmount = $this->userScoreService->getDeductScoreAmount($userId, $payAmount - $groupOrderPayProduct->forbidScoreAmount);

    $orderInfo->deductScore = $this->userScoreService->changeDeductScore($orderInfo->deductScoreAmount);
    $orderInfo->canDeductScore = $orderInfo->deductScore;
    // }

    $orderInfo->payAmount =round( $payAmount + $orderInfo->freightAmount,2);
    $orderInfo->amount = round($payAmount, 2);
    // ????????????
    $orderInfo->discountAmount = $groupOrderPayProduct->discountAmount;// $orderInfo->scoreAmount;//   $groupOrderPayProduct->discountAmount;

    $orderInfo->productScore = $groupOrderPayProduct->productScore;
    $orderInfo->needScore = $groupOrderPayProduct->needScore;

    $orderInfo->merchantId = $groupOrderPayProduct->merchantId;
    $orderInfo->needScore = $groupOrderPayProduct->needScore;
    $orderInfo->supplyName = $groupOrderPayProduct->supplyName;
    $orderInfo->isVip = $isVip;
    return $orderInfo;
  }

  /**
   * ???????????????????????????
   * @param CalculateOrderInfoRequest $request `
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
    $userId = parent::getUserId();

    $distribute = $request->distribute;
    $couponDiscount = $request->couponDiscount;
    $scoreDiscount = $request->scoreDiscount;
    $userCouponId = $request->userCouponId;
    $result->setAttributes(ArrayHelper::toArray(json_decode($orderInfo)), false);
    $result->userCouponId = $userCouponId;
    $amount = $result->amount * 1;

    $productFreightAmount = $this->orderPayService->getProductFreightAmount($request->productList, empty(parent::getUserCacheByName('is_vip')) ? 0 : 1, $request->cityCode);

    $result->productFreight = $productFreightAmount;

    if (empty($orderInfo->isVirtual)) {
      $result->freightAmount = $this->calculateFreightAmount($amount, $distribute, $result->productFreight);
    }
    //?????????Id ,???????????????
    $result->payAmount =round(  $result->amount + $result->freightAmount,2);

    if (!empty($userCouponId)) {
      $result->couponAmount = $this->calculateCouponAmount($amount, $couponDiscount, $userCouponId);;
      $result->payAmount =round(   $result->payAmount - $result->couponAmount,2);

    }
    if (!empty($scoreDiscount)) {

      $result->scoreAmount = $this->calculateScoreAmount($amount, $scoreDiscount, $userId);
      $result->deductScore = $this->userScoreService->changeDeductScore($result->scoreAmount);

      $result->payAmount = round(   $result->payAmount - $result->scoreAmount,2);
    } else {
      $result->scoreAmount = 0;
      $result->deductScore = 0;
    }

    return $result;
  }


  /**
   * ??????/?????????????????????????????????id???
   * @return mixed
   * @throws \yii\web\UnprocessableEntityHttpException
   */
  public function actionOpenOrJoinAssemble()
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
   * ????????????????????????
   * @return mixed
   * @throws UnprocessableEntityHttpException
   */
  public function actionDirectPay()
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
   * ????????????????????????
   * @param request
   * @return
   */
  public function actionWxPayNotify()
  {
    //???????????????xml
    $testxml = file_get_contents("php://input");
    //???xml?????????json??????
    $jsonxml = json_encode(simplexml_load_string($testxml, 'SimpleXMLElement', LIBXML_NOCDATA));
    //????????????
    $data = json_decode($jsonxml, true);
    //????????????????????????????????????sign
    $data_sign = $data['sign'];
    $sign = $this->wxPayService->makeSign($data);
    //????????????????????????,??????????????????
    if (($sign === $data_sign) && ($data['return_code'] == 'SUCCESS') && ($data['result_code'] == 'SUCCESS')) {
      $results = $data;
      //??????????????????????????????
      $order_sn = $data['out_trade_no'];    //?????????
      $attach = $data['attach'];        //????????????,????????????pinkID
      $openid = $data['openid'];            //?????????openID
      $total_fee = $data['total_fee'] / NumberEnum::HUNDRED;    //????????????


      $proxyPay = $this->proxyPayService->getOneById($order_sn);
      if (empty($proxyPay)) {
        return WxNotifyEnum::FAIL;
      }
      $order_sn = $proxyPay['order_id'];
      //???????????????????????????????????????????????????
      $orderInfo = $this->service->getOneById($order_sn);
      if (empty($orderInfo) || !(OrderStatusEnum::UNPAID === $orderInfo['status'])) {
        //???????????????????????????????????????????????????
        return WxNotifyEnum::SUCCESS;
      }
      //??????????????????????????????
      if (!$this->service->notifyOrderById($orderInfo)) {
        return WxNotifyEnum::FAIL;
      }
      $this->on(self::EVENT_ORDER_PAY, ['api\modules\mobile\service\event\DistributeEventService', 'distribute']);
      //????????????
      $this->userInfoService->lockScore(-1 * $orderInfo['deduct_score'], $orderInfo['user_id']);
      if ($orderInfo['order_product_type'] == StrategyTypeEnum::PINK) {
        $this->on(self::EVENT_ORDER_PAY, ['api\modules\mobile\service\event\PinkEventService', 'pinkProduct']);
      }

      $event = new OrderPayEvent();
      $event->openId = $openid;
      $event->payAmount = $orderInfo['pay_amount'];
      $event->orderInfo = $orderInfo;

      $this->trigger(self::EVENT_ORDER_PAY, $event);

    } else {
      $results = false;
    }
    //??????????????????????????????
    if ($results) {
      $str = WxNotifyEnum::SUCCESS;
    } else {
      $str = WxNotifyEnum::FAIL;
    }
    return $str;

  }

  /**
   * ??????????????????
   * ??????????????????
   * ??????????????????
   * @param $id
   * @param $isPink
   * @param null $pinkId
   * @return CalculateOrderInfoResult
   * @throws FanYouHttpException
   */
  private function verifyOrderSubmit($id, $isPink = 0, $pinkId = null): CalculateOrderInfoResult
  {
    $cacheKey = CacheEnum::getPrefix(OrderPayEnum::CALCULATE_ORDER . $id);
    $orderInfo = parent::getCache($cacheKey);
    if (empty($orderInfo)) {
      throw new  FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, ErrorOrder::PAGE_OUT_TIME);
    }
    $result = new CalculateOrderInfoResult();
    $result->setAttributes(ArrayHelper::toArray(json_decode($orderInfo)), false);

    $checkResult = $this->orderPayService->checkStock($result->productList);
    if (empty($checkResult)) {
      throw new  FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, $checkResult);
    }
    if (!$isPink && $checkResult == StrategyTypeEnum::PINK) {
      $checkResult = StrategyTypeEnum::NORMAL;
    }
    $result->orderProductType = $checkResult;

    if ($isPink) {
      $result->productList = $this->orderPayService->takePink($result->productList, $id, $pinkId);
    }
    return $result;
  }


  /**
   * ????????????????????????????????????????????????
   * @param CalculateOrderInfoResult $calculate
   * @return BasicOrderInfoModel
   */
  private function changeToOrderInfo(CalculateOrderInfoResult $calculate): BasicOrderInfoModel
  {

    $couponAmount = $calculate->couponAmount;
    $scoreAmount = $calculate->scoreAmount;

    $model = new BasicOrderInfoModel();

    $model->setAttributes(StringHelper::toUnCamelize($calculate->toArray()), false);


    if (empty($scoreAmount)) {
      $model->deduct_score = 0;
    }
    $model->short_order_no = $this->service->countToday() + NumberEnum::ONE;

    $discountAmount = $couponAmount + $scoreAmount;   //$calculate->discountAmount+

    $model->discount_snap = json_encode(['productDiscount' => $calculate->discountAmount, 'couponDiscount' => $couponAmount, 'scoreDiscount' => $scoreAmount]);
    $model->cart_snap = json_encode($calculate->productList, JSON_UNESCAPED_UNICODE);
    //????????????
    $model->discount_amount = $discountAmount;
    $remainTime = empty($_ENV['ORDER_REMAIN_TIME']) ? 900 : $_ENV['ORDER_REMAIN_TIME'];
    $model->un_paid_seconds = time() + $remainTime;

    return $model;
  }

  /**
   * ????????????
   * @param $amount
   * @param int $distribute
   * @return float|int
   */
  private function calculateFreightAmount($amount, $distribute = 0, $productFreight = 0): float
  {
    if ($distribute) {
      return 0;
    }
    return round($this->freightService->getFreightAmount($amount, $distribute) + $productFreight, 2);
  }

  /**
   * ????????????????????????
   * @param $amount
   * @param $couponDiscount
   * @param $userCouponId
   * @return float
   * @throws FanYouHttpException
   */
  private function calculateCouponAmount($amount, $couponDiscount, $userCouponId): float
  {
    $couponAmount =
      round($this->couponUserService->getCouponPrice($userCouponId, $amount), 2);
    if (!($couponAmount == $couponDiscount)) {
      throw new  FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, ErrorOrder::COUPON_UN_EFFECT);
    }
    return $couponAmount;

  }

  /**
   * ????????????????????????
   * @param $amount
   * @param $scoreDiscount
   * @param $userId
   * @return float
   * @throws FanYouHttpException
   */
  private function calculateScoreAmount($amount, $scoreDiscount, $userId): float
  {
    $scoreAmount =
      round($this->userScoreService->getDeductScoreAmount($userId, $amount), 2);
    if (empty($scoreAmount) || !($scoreAmount == $scoreDiscount)) {
      throw new  FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, ErrorOrder::SCORE_UN_EFFECT);
    }
    return $scoreAmount < 0 ? 0 : $scoreAmount;

  }

  /**
   * ??????????????????????????????
   * @return BasicOrderInfoModel
   * @throws FanYouHttpException
   */
  public function actionQueryIsPay()
  {
    $orderId = parent::getRequestId();
    $orderInfo = BasicOrderInfoModel::find()->select(['id', 'status'])->where(['id' => $orderId])->asArray()->one();
    if (empty($orderInfo)) {
      throw new  FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, '??????????????????');
    }
    if ($orderInfo->status == OrderStatusEnum::UNPAID) {
      //??????????????????
      $data = $this->wxPayService->getPayOrderInfo($orderId);
      $data_sign = $data['sign'];
      $sign = $this->wxPayService->makeSign($data);
      //????????????????????????,??????????????????
      if (($sign === $data_sign) && ($data['return_code'] == 'SUCCESS')) {
        if (($data['result_code'] == 'FAIL')) {
          throw new  FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, $data['err_code_des']);
        } elseif (empty($data['err_code']) && ($data['trade_state'] == 'SUCCESS')) {
          //??????????????????????????????
          $this->service->notifyOrderById($orderInfo);
          $orderInfo['status'] = OrderStatusEnum::UN_SEND;
        }
      }
    }
    return $orderInfo;
  }


  public function actionTestJob()
  {
    ThirdSmsService::batchSendSms();
//        $event = new OrderPayEvent();
//        $orderInfo = BasicOrderInfoModel::find()->where(['id' => "20201120-133842-64691"])->asArray()->one();
//        $event->orderInfo = $orderInfo;
//        MsgEventService::actionSendRegMsg($event);
  }

}
/**********************End Of UserInfo ?????????************************************/ 



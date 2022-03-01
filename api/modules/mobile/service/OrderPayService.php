<?php

namespace api\modules\mobile\service;

use api\modules\mobile\models\forms\BasicOrderInfoModel;
use api\modules\mobile\models\forms\FreightTemplateModel;
use api\modules\mobile\models\forms\OrderPayProductGroup;
use api\modules\mobile\models\forms\PinkConfigModel;
use api\modules\mobile\models\forms\PinkModel;
use api\modules\mobile\models\forms\PinkPartakeModel;
use api\modules\mobile\models\forms\ProductModel;
use api\modules\mobile\models\forms\WxPayRequest;
use api\modules\mobile\models\request\ProductOrderSingle;
use api\modules\seller\models\forms\FreightTemplateDetail;
use fanyou\common\ProductOrderAmount;
use fanyou\enums\AppEnum;
use fanyou\enums\entity\CouponEnum;
use fanyou\enums\entity\OrderStatusEnum;
use fanyou\enums\entity\StrategyTypeEnum;
use fanyou\enums\HttpErrorEnum;
use fanyou\enums\NumberEnum;
use fanyou\enums\PayStatusEnum;
use fanyou\enums\StatusEnum;
use fanyou\enums\WalletStatusEnum;
use fanyou\error\errorCode\ErrorProduct;
use fanyou\error\FanYouHttpException;
use fanyou\tools\ArrayHelper;
use fanyou\tools\StringHelper;
use Yii;

/**
 * Class OrderPayService
 * @package api\modules\mobile\service
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-04-20 15:13
 */
class OrderPayService extends BasicService
{

    private $userInfoService;
    private $basicOrderInfoService;
    private $productService;
    private $saleProductStrategyService;
    private $userCheckCodeService;

    public function __construct()
    {
        $this->productService = new ProductService();
        $this->saleProductStrategyService = new SaleProductStrategyService();
        $this->basicOrderInfoService = new BasicOrderInfoService();
        $this->userCheckCodeService = new UserCheckCodeService();
        $this->userInfoService = new UserInfoService();

    }


    /*********************Coupon模块服务层************************************/
    /**
     * 将前端传入的商品分组
     * @param array $productList
     * @param $isVip
     * @param $city
     * @param $fullPay
     * @return array
     * @throws FanYouHttpException
     */
    public function groupOrderPayProduct(Array $productList, $isVip = false, $city = 0,$fullPay=0)
    {
        $merchantId = AppEnum::MERCHANTID;
        $supplyName='';
        $group = new OrderPayProductGroup();
        // 计算商品价格
        $amount = 0;
        $originAmount = 0;
        $salesAmount = 0;
        $productScore = 0;
        $needScore=0;
        $productFreight = 0;
        //不可积分抵扣金额
        $forbidScoreAmount = 0;
        $forbidCouponAmount = 0;
        $productResult = [];
        $payType=PayStatusEnum::allType();
        foreach ($productList as $key => $value) {

            $p = new ProductOrderSingle($value);
            $productId = $p['id'];// $value['id'];
            $number = $p->number;// $value['number'];
            $productInfo = $this->productService->getOneById($productId);
            if (empty($productInfo)) {
                //如果商品不存在
                throw new  FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, $productInfo->name . ErrorProduct::UN_EXISTS);
            }
            $merchantId = $productInfo->merchant_id;
            //获取商品售价
            $entity = $this->getProductAmount($productInfo, $p->skuId);
            if (empty($entity)) {
                //如果商品不存在
                throw new   FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, $productInfo->name . ErrorProduct::NOT_SALE);
            }

            if ($productInfo->is_sku) {
                $p->specSnap = $entity->spec_snap;
                $p->stockId = $p->skuId;
            } else {
                $p->stockId = (string)$productId;
            }
            $p->supplyName=$productInfo->supply_name;
            $supplyName=$p->supplyName;
            $group->supplyName=$supplyName;

            $group->payCategoryIds[$key] = $productInfo->category_id;;
            $group->payProductIds[$key] = $productId;
            $group->cartIds[$key] = $p->cartId;


            if(!empty($fullPay)){
                $currencyPrice =   $entity->origin_price;
            }else{
                $currencyPrice = $isVip ? $entity->member_price : $entity->sale_price;
            }
            //TODO 需不需要？
            //积分商城不应该加快递费
            if ($productInfo->is_freight) {
                $productFreight +=
                    $this->calculateProductFreightAmount($city, $productInfo->freight_id, $currencyPrice, $number, $productInfo->weight, $productInfo->volume);
            }
            if (empty($productInfo->support_score)) {
                $forbidScoreAmount += $currencyPrice * $number;
            }
            if (empty($productInfo->support_coupon)) {
                $forbidCouponAmount += $currencyPrice * $number;
            }
            $p->isDistribute = $productInfo->is_distribute;

            $p->name = $productInfo->name;
            $p->coverImg = $productInfo->cover_img;
            $p->images = $productInfo->images;
            $p->saleStrategy = $productInfo->sale_strategy;
            $p->strategyType = $productInfo->sale_strategy;

            $p->salePrice = $currencyPrice;

            $p->originPrice = $entity->origin_price;
            $p->stockNumber = $entity->stock_number;
            $p->costAmount=$productInfo->cost_price* $number;

            $p->amount = round($currencyPrice * $number,2);
            $p->commandId=$productInfo->command_id;
            $p->isGoodRefund=$productInfo->is_good_refund;
            $p->needScore=$productInfo->need_score;
            $payType= array_intersect($payType, json_decode($productInfo->pay_type));

            $productResult[] = $p;

            $amount += $currencyPrice * $number;
            $originAmount += $entity->origin_price * $number;
            $salesAmount += $entity->sale_price * $number;
            $productScore += !empty($productInfo->product_score) ? $productInfo->product_score * $number : 0;
            $needScore += !empty($productInfo->need_score) ? $productInfo->need_score * $number : 0;
        }

        $group->forbidCouponAmount = $forbidCouponAmount;
        $group->payType=array_values($payType);
        $group->forbidScoreAmount = $forbidScoreAmount;
        $group->productFreight = $productFreight;
        $group->productAmount =round( $amount,2);
        $group->originAmount = $originAmount;
        $group->discountAmount = $salesAmount - $amount;
        $group->productList = $productResult;
        $group->productScore = $productScore;
        $group->merchantId = $merchantId;

        $group->needScore=$needScore;
        return $group->toArray();
    }


    public function getProductFreightAmount(Array $productList, $isVip = false, $city = 0)
    {
        $productFreight = 0;
        foreach ($productList as $key => $value) {
            $p = new ProductOrderSingle($value);
            $productId = $p->id;// $value['id'];
            $number = $p->number;// $value['number'];
            $productInfo = $this->productService->getOneById($productId);
            if (empty($productInfo)) {
                //如果商品不存在
                throw new  FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, $productInfo->name . ErrorProduct::UN_EXISTS);
            }
            //获取商品售价
            $entity = $this->getProductAmount($productInfo, $p->skuId);
            if (empty($entity)) {
                //如果商品不存在
                throw new   FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, $productInfo->name . ErrorProduct::NOT_SALE);
            }

            $currencyPrice = $isVip ? $entity->member_price : $entity->sale_price;
            //积分商城不应该加快递费
            if ($productInfo->is_freight) {
                $productFreight +=
                    $this->calculateProductFreightAmount($city, $productInfo->freight_id, $currencyPrice, $number, $productInfo->weight, $productInfo->volume);
            }

        }
        return $productFreight;
    }


    /**
     * 计算商品的当前售价
     * @param ProductModel $productModel
     * @param $skuId
     * @return ProductOrderAmount
     */
    public function getProductAmount(ProductModel $productModel, $skuId): ProductOrderAmount
    {
        $result = new ProductOrderAmount();
        if (!empty($productModel) && $productModel->is_on_sale) {

            $id = $productModel->id;
            //商品如果是sku商品，则查询对应sku价格
            if ($productModel->is_sku) {
                $id = $skuId;
            }
            $saleStrategy = $productModel->sale_strategy;
            //如果该商品有促销策略
//            if (!empty($saleStrategy)) {
//                //当前商品策略价格
//                $productInfo = $this->getProductSaleStrategyAmount($id, time());
//            }

            //如果活动策略失效，则查sku价格
            //  if (empty($productInfo) || empty($saleStrategy)) {
            //查一下sku价格
            $productInfo = $this->productService->getSkuById($id);
            //    }
            //如果价格能计算出来
            if (!empty($productInfo)) {
                $result->setAttributes($productInfo->toArray(), false);

            } else {
                $result->setAttributes($productModel->toArray(), false);
            }
        }
        return $result;
    }

    /**
     *  计算活动商品的价格
     * @param $productId
     * @param $now
     * @return ProductOrderAmount
     */
    public function getProductSaleStrategyAmount($productId, $now): ?ProductOrderAmount
    {
        //当前商品生效策略
//        switch ($saleStrategy) {
//            case ProductStrategyEnum::GROUP_BUY:
//                break;
//            case ProductStrategyEnum::SEC_KILL:
//                //查看策略是否生效，生效，查价格
//
//                break;
//        }
        $group = new ProductOrderAmount();
        $strategyEffect = $this->isSaleProductEffect($productId, $now);
        //是否在促销
        if (empty($strategyEffect)) {
            //    throw  new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,ErrorProduct::UN_EFFECT_STRATEGY);
            return null;
        }
        //查询价格,,商品活动表
        $group->setAttributes(ArrayHelper::toArray($this->saleProductStrategyService->getOneById($productId)), false);
        return $group;
    }

    /**
     * 查看商品活动是否生效
     * @param $productId
     * @param $now
     * @return bool
     */
    public function isSaleProductEffect($productId, $now): bool
    {
        $result = false;
        new ProductOrderAmount();
        $saleProduct = $this->saleProductStrategyService->getSaleProductById($productId);
        if ($saleProduct->status === StatusEnum::ENABLED) {
            //如果结束时间大于当前时间
            if (strtotime($saleProduct->start_date) <= $now && strtotime($saleProduct->end_date) > $now) {

                $nowHour = date("H");
                if ($saleProduct->start_hour <= $nowHour && $saleProduct->end_hour > $nowHour) {
                    $result = true;
                }
            }
        }
        return $result;

    }

    /**
     * 获取订单中可以使用的优惠券
     * @param $couponList
     * @param $group
     * @return array
     */
    public function getCanUseCoupon($couponList, $group)
    {    //-$group['forbidCouponAmount']
        return $this->calculateUseCoupon($couponList, array_unique($group['payCategoryIds']), array_unique($group['payProductIds']), $group['productAmount'] - $group['forbidCouponAmount']);

    }

    /**
     * 获取订单中可以使用的优惠券
     * @param $couponList
     * @param $payCategoryIds
     * @param $payProductIds
     * @param $limitAmount
     * @return array
     */
    public function calculateUseCoupon($couponList, $payCategoryIds, $payProductIds, $limitAmount)
    {

        if (!empty($couponList)) {
            $couponResult = [];
            foreach ($couponList as $k => $coupon) {

                switch ($coupon['type']) {
                    case CouponEnum::COMMON :
                        if (!empty($coupon['limit_amount']) && ($coupon['limit_amount'] <= $limitAmount)) {
                            $coupon['enable'] = 1;
                            $couponResult[] = $coupon;
                        }
                        break;
                    case CouponEnum::CATEGORY :

                        $categoryArr = $coupon['type_relation_id'];

                        $categoryCouponList = explode(",", $categoryArr);

                        foreach ($categoryCouponList as $key => $value) {

                            if ($this->is_in_array($value, $payCategoryIds)) {
                                if (!empty($coupon['limit_amount']) && ($coupon['limit_amount'] <= $limitAmount)) {
                                    $coupon['enable'] = 1;
                                    unset($coupon['type_relation_id']);
                                    $couponResult[] = $coupon;
                                }
                            }
                        }
                        break;
                    case CouponEnum::PRODUCT :
                        $productArr = $coupon['type_relation_id'];
                        $productCouponList = explode(",", $productArr);
                        foreach ($productCouponList as $key => $value) {
                            if (in_array($value, $payProductIds)) {
                                if (!empty($coupon['limit_amount']) && ($coupon['limit_amount'] <= $limitAmount)) {
                                    $coupon['enable'] = 1;
                                    unset($coupon['type_relation_id']);
                                    $couponResult[] = $coupon;
                                }
                            }
                        }
                        break;
                }
                //  print_r($couponResult);exit;
            }//end  if(empty($couponList))
            return $couponResult;
        }
    }

    /**
     * 检测库存是否足够
     * @param $productList
     * @param string $orderId
     * @return mixed|string
     * @throws FanYouHttpException
     * @throws \Throwable
     */
    public function checkStock($productList)
    {
        return $this->productService->checkStock($productList);
    }

    /**
     * 组装拼团信息
     * @param $productList
     * @param string $orderId
     * @param string $pinkId
     * @return array
     * @throws \Throwable
     */
    public function takePink($productList, $orderId = '', $pinkId = '')
    {
        $cartList = [];
        $now= time();
        $userId = Yii::$app->user->identity['id'];
        foreach ($productList as $k => $value) {
            $result = $value['saleStrategy'];
            $productId = $value['id'];
            if ($result == StrategyTypeEnum::PINK) {
                if (!empty($pinkId)) {
                   $exsistPink= PinkModel::findOne($pinkId);
                   //不为空，且结束，置空
                   if(empty($exsistPink)||$exsistPink->status!=StatusEnum::STATUS_INIT){
                       $pinkId='';
                   }
                    //如果人满，重新发起
                   if($exsistPink->currency_num>=$exsistPink->total_num){
                        $pinkId='';
                    }
                    //如果时间到，重新发起
                    if($exsistPink->end_time<=time()){
                        $pinkId='';
                    }
                }

                if (empty($pinkId)) {
                    $pinkConfig = PinkConfigModel::findOne(['product_id' => $productId]);
                    if (!empty($pinkConfig)) {
                        if($pinkConfig->start_time>$now){
                            throw new  FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, '团购未开始');
                        }
                        if($pinkConfig->end_time<$now){
                            throw new  FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, '团购已结束');
                        }
                        //创建拼团
                        $model = new PinkModel();
                        $model->is_effect=NumberEnum::ZERO;
                        $model->status = NumberEnum::ZERO;
                        $model->currency_num = NumberEnum::ZERO;
                        $model->user_id = $userId;
                        $model->user_name = Yii::$app->user->identity['nick_name'];;
                        $model->total_num = $pinkConfig['people'];
                        $model->product_id = $productId;
                        $model->end_time = time() + $pinkConfig['remain_time'];
                        $model->product_snap= json_encode(['name'=>$value['name']],JSON_UNESCAPED_UNICODE);
                        $model->insert();
                        $pinkId = $model->primaryKey;
                    }
                }
                //团购下单记录,如果带了团购Id，则提交订单需传入pinkId
                $partake = new PinkPartakeModel();
                $partake->user_id = $userId;
                $partake->app_open_id=Yii::$app->user->identity['mini_app_open_id'];
                $partake->status = NumberEnum::ZERO;
                $partake->pink_id = $pinkId;
                $partake->nick_name = Yii::$app->user->identity['nick_name'];
                $partake->head_img = Yii::$app->user->identity['head_img'];
                $partake->order_id = $orderId;
                $partake->insert();
            }
            $value['pinkId']=$pinkId;
            $cartList[]=$value;
        }
        return $cartList;
    }


    /**
     * 拉起微信JS支付
     * @param $orderId
     * @param $openid
     * @return array|null
     */
    public function getWxPayRequest($orderId, $openid,$tradeType='JSAPI'): ?array
    {
        $model = new WxPayRequest($openid,$tradeType);

        $orderInfo = BasicOrderInfoModel::find()->where(['id'=>$orderId])->asArray()->one();

        if ($orderInfo['status'] === OrderStatusEnum::UNPAID) {
            $model->total_fee = $orderInfo['pay_amount'] * NumberEnum::HUNDRED;
            if ($model->total_fee == 0) {
                $model->total_fee = 1;
            }
            $model->out_trade_no =StringHelper::random(32) ;

            return $model->toArray();
        }
        return [];
    }

    /**
     * 计算商品邮费
     * @param $freightId
     * @param $price
     * @param $number
     * @param $weight
     * @param $volume
     * @param $city
     * @return int
     */
    public function calculateProductFreightAmount($city, $freightId, $price, $number, $weight, $volume)
    {
        $freightAmount = 0;
        $freight = FreightTemplateModel::findOne($freightId);
        if (empty($freight)) {
            return $freightAmount;
        }
        //开启免邮，并且  免邮设置
        if ($freight['is_free_post'] && !empty($freight['post_snap'])) {
            $postList = ArrayHelper::toArray(json_decode($freight['post_snap']));
            if ($this->isFreePostFreight($city, $postList, $number, $price)) {
                return $freightAmount;
            }
        }
        $freightDetail = $this->findFreightDetail(ArrayHelper::toArray(json_decode($freight['freight_snap'])), $city);
        switch ($freight['type']) {
            case NumberEnum::ZERO:
                $freightAmount = $this->realCalculateFreightAmount($freightDetail, NumberEnum::ONE, $number);
                break;
            case NumberEnum::ONE:
                $freightAmount = $this->realCalculateFreightAmount($freightDetail, $number, $weight);
                break;
            case NumberEnum::TWO:
                $freightAmount = $this->realCalculateFreightAmount($freightDetail, $number, $volume);

                break;
            default:
                break;
        }
        return $freightAmount;
    }

    /**
     * 获取配送区域及运费
     * @param $freightList
     * @param $city
     * @return FreightTemplateDetail
     */
    private function findFreightDetail($freightList, $city)
    {
        $detail = new FreightTemplateDetail();
        if (!empty($city)) {
            $province = substr($city, 0, 2);
            $city = substr($city, 0, 4);
            foreach ($freightList as $item) {
                $cityId = $item['id'];
                if (strlen($cityId) > 2) {
                    $cityId = substr($cityId, 3, 4);
                }
                if ($cityId == $city) {
                    $detail->setAttributes(StringHelper::toCamelize($item), false);
                    return $detail;
                } else if ($cityId == $province) {
                    $detail->setAttributes(StringHelper::toCamelize($item), false);
                    return $detail;
                }
            }
        }
        $detail->setAttributes(StringHelper::toCamelize($freightList[0]), false);
        return $detail;
    }

    /**
     * 是否免邮
     * @param $city
     * @param $postList
     * @param $number
     * @param $price
     * @return array|bool
     */
    private function isFreePostFreight($city, $postList, $number, $price)
    {
        $array = [];
        if ($postList == null || count($postList) == 0) {
            return $array;
        }
        $province = 0;
        if (!empty($city)) {
            $province = substr($city, 0, 2);
            $city = substr($city, 0, 4);
        }

        foreach ($postList as $item) {

            $cityId = $item['id'];
            if (strlen($cityId) > 2) {
                $cityId = substr($cityId, 3, 4);
            }
            if ($cityId == $city) {
                $array = $item;

                break;
            } else if ($cityId == $province) {
                $array = $item;
                break;
            }
        }
        //      print_r($array);exit;
        if (!empty($array)) {
            //满额包邮
            if (isset($array['limit_amount']) && ($number * $price >= $array['limit_amount'])) {
                return true;
            }
            //包邮数量
            if (isset($array['number']) && ($number >= $array['number'])) {
                return true;
            }
        }
        return false;

    }

    /**
     * 真正计算邮费
     * @param FreightTemplateDetail $detail
     * @param $number
     * @param $typeParam
     * @return float|int
     */
    private function realCalculateFreightAmount(FreightTemplateDetail $detail, $number, $typeParam)
    {
        $baseAmount = $detail->firstAmount;
        $isUnOverLoad = $detail->first >= $typeParam ? 1 : 0;
        $caWeight = $isUnOverLoad ? $typeParam : $detail->first;
        if ($number == NumberEnum::ONE && $isUnOverLoad) {
            return $baseAmount;
        }
        if (empty($detail->other)) {
            $detail->other = NumberEnum::ONE;
        }
        $count = ceil(($typeParam * $number - $caWeight) / ($detail->other));
        return $baseAmount + $detail->otherAmount * $count;

    }


    function is_in_array($id, $ids)
    {
        foreach ($ids as $key => $v) {
            $idList = json_decode($v, true);
            if (in_array($id, $idList))
                return true;
        }
        return false;
    }
}
/**********************End Of Coupon 服务层************************************/


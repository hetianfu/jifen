<?php

namespace api\modules\mobile\service\event;

use api\modules\mobile\models\event\OrderPayEvent;
use api\modules\mobile\models\event\TaskEvent;
use api\modules\mobile\models\forms\BasicOrderInfoModel;
use api\modules\mobile\models\forms\CouponModel;
use api\modules\mobile\models\forms\CouponUserModel;
use api\modules\mobile\models\forms\ProductSkuModel;
use api\modules\mobile\service\BasicService;
use api\modules\seller\models\forms\ProductModel;
use fanyou\common\StockOrder;
use fanyou\enums\entity\OrderStatusEnum;
use fanyou\enums\StatusEnum;
use fanyou\tools\ArrayHelper;
use yii\db\Expression;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-04-23
 */
class CouponEventService extends BasicService
{
    /*********************支付订单成功后，事件************************************/

    /**
     * 支付后，获取符合条件的卡券
     * 支付后，系统查询卡券，发放给用户
     * @param OrderPayEvent $event
     * @return CouponUserModel|null
     * @throws \Throwable
     */
    public static  function getEffectCouponAfterPay(OrderPayEvent  $event)
    {
        //发放新卡券
        $couponUser=new CouponUserModel();
        $model=  CouponModel::find()
            ->where(['is_once'=>StatusEnum::DISABLED])
            ->andWhere(['>', 'left_number', StatusEnum::STATUS_INIT])

            ->andWhere(['>', 'order_pay_line',$event->payAmount])
            ->andWhere(['is_pay_send' => StatusEnum::ENABLED])
            ->joinWith('template')
            ->orderBy(['order_pay_line'=>SORT_DESC,'totime'=>SORT_DESC])
            ->one();

        if(!empty($model)){

        $couponUser->title=$model['template']['title'];
        $couponUser->type=$model['template']['type'];
        $couponUser->amount=$model['template']['amount'];
        $couponUser->limit_amount=$model['template']['limit_amount'];
        $couponUser->merchant_id=$model['template']['merchant_id'];
        $couponUser->type_relation_id=$model['template']['type_relation_id'];
        $couponUser->fromtime=time();
        $couponUser->totime=$couponUser->fromtime+$model['template']['effect_days']*24*3600;
        $couponUser->editor=$model['editor'];
        $couponUser->coupon_id=$model['id'];
        $couponUser->user_id=$event->orderInfo['user_id'];
        //$couponUser->insert();

        return $couponUser;
        }
    }
    /**
     * 定时任务
     * 取消订单，回滚卡券状态
     * @param TaskEvent $event
     */
    public static function rollUserCouponTask(TaskEvent $event)
    {
        $orderList=$event->orderList;
        foreach ($orderList as $k=>$v){
            $userCouponId=$v['user_coupon_id'];
            if(!empty($userCouponId)){
            CouponUserModel::updateAll(['status'=>StatusEnum::UN_USED],['id'=>$userCouponId]);
            }
        }

    }

}
/**********************End Of Coupon 服务层************************************/


<?php

namespace api\modules\seller\service\event;

use api\modules\seller\models\event\OrderEvent;
use api\modules\seller\models\forms\BasicOrderInfoModel;
use api\modules\seller\models\forms\UserCheckCodeModel;
use api\modules\seller\models\forms\UserCheckCodeRecordModel;
use api\modules\seller\models\forms\UserInfoModel;
use api\modules\seller\service\BasicService;
use fanyou\enums\StatusEnum;
use fanyou\service\SendWxMsgService;
use fanyou\tools\ArrayHelper;
use Yii;
use yii\db\Expression;

/**
 * Class CategoryEventService
 * @package api\modules\seller\service\event
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-06-10 10:42
 */
class OrderEventService extends BasicService
{
    /**
     * @param OrderEvent $event
     * @throws \Throwable
     */
    public static  function checkOrder(OrderEvent  $event)
    {
        $orderId=$event->id;
        $number=$event->number;
        $orderInfo=BasicOrderInfoModel::findOne($orderId);
        //减少用户卡券，
        //增加核销记录
        $model= UserCheckCodeModel::findOne(['order_id'=>$orderId]);
        $update=UserCheckCodeModel::updateAll(['status'=>StatusEnum::USED,'left_number'=> new Expression('`left_number` - ' . $number)
            ,'used_number'=> new Expression('`used_number` + ' . $number)
            // ,'total_number'=> new Expression('`total_number` - ' . $number)
           ] ,
            ['id'=>$model->id]);
        if($update){

            //添加核销记录
            $detail=new UserCheckCodeRecordModel();
            $detail->title=$model->title;
            $detail->check_shop_id=$model->check_shop_id;
            $detail->bar_qrcode=$model->bar_qrcode;
            $detail->number=$number;
            $detail->order_id=$orderId;

            $detail->user_id=$orderInfo->user_id;
            $detail->check_employee_id=Yii::$app->user->identity['id'];
            $detail->insert();

            $userInfo=UserInfoModel::findOne($orderInfo->user_id);
            $openId=$userInfo->mini_app_open_id;

            SendWxMsgService::orderCheckMessage(
                $orderId, $openId, ArrayHelper::toArray(json_decode($orderInfo->cooperate_shop_address_snap))['shopName'],$orderInfo->show_bar_qrcode );

        }






    }


}
/**********************End Of Coupon 服务层************************************/


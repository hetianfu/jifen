<?php

namespace api\modules\seller\service\event;

use api\modules\seller\models\event\OrderEvent;
use api\modules\seller\models\forms\BasicOrderInfoModel;
use api\modules\seller\models\forms\UserInfoModel;
use api\modules\seller\service\BasicService;
use fanyou\service\SendWxMsgService;
use fanyou\service\ThirdSmsService;
use fanyou\tools\ArrayHelper;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-03-23
 */
class MessageEventService extends BasicService
{
    public function __construct()
    {
    }

    /*********************Product模块服务层************************************/

    /**
     * 订单退货通知
     * @param OrderEvent $event
     */
    public static function refundOrder(OrderEvent $event)
    {
        $order = BasicOrderInfoModel::findOne($event->id);
        //if (!empty($_ENV['THIRD_SMS_STATUS'])) {
            $connect=ArrayHelper::toArray( json_decode($order['connect_snap'] ))  ;
            ThirdSmsService::refundSms($connect['name'], $connect['telephone']);
        //}
    }

    /**
     * 订单已发货通知
     * @param OrderEvent $event
     */
    public static function orderSendMessage(OrderEvent $event)
    {
        $orderId = $event->id;
        $order = BasicOrderInfoModel::findOne($orderId);

      $connect=ArrayHelper::toArray( json_decode($order['connect_snap'] ))  ;
      ThirdSmsService::sendSms($connect['name'],$connect['telephone'],$order['express_no']);
//        if(!empty($_ENV['THIRD_SMS_STATUS'])){
//            $connect=ArrayHelper::toArray( json_decode($order['connect_snap'] ))  ;
//            ThirdSmsService::sendSms($connect['name'],$connect['telephone'],$order['express_no']);
//        }else{
//
//
//        $userInfo = UserInfoModel::findOne($order->user_id);
//        $openId = $userInfo->mini_app_open_id;
//        SendWxMsgService::orderSendMessage(
//            $orderId, $openId, $order->express_name, $order->express_no);
//        }
    }

    /**
     * 提现结果通知
     * @param OrderEvent $event
     */
    public static function drawCashMessage(OrderEvent $event)
    {

        SendWxMsgService::drawCashMessage(
            $event->number, $event->id, $event->success);

    }


}
/**********************End Of Product 服务层************************************/


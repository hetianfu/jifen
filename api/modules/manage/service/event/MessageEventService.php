<?php

namespace api\modules\manage\service\event;

use api\modules\manage\models\event\OrderEvent;
use api\modules\seller\models\forms\BasicOrderInfoModel;
use api\modules\seller\models\forms\UserInfoModel;
use fanyou\service\SendWxMsgService;
use fanyou\tools\ArrayHelper;


/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-03-23
 */
class MessageEventService
{


    /*********************Product模块服务层************************************/
    /**
     * 核销通知
     * @param OrderEvent $event
     */
    public static  function checkOrder(OrderEvent  $event)
    {
        $orderId=$event->id;
        $orderInfo=BasicOrderInfoModel::findOne($orderId);
        $userInfo=UserInfoModel::findOne($orderInfo->user_id);
        $openId=$userInfo->mini_app_open_id;
        SendWxMsgService::orderCheckMessage(
            $orderId, $openId, ArrayHelper::toArray(json_decode($orderInfo->cooperate_shop_address_snap))['shopName'],$orderInfo->show_bar_qrcode );


    }

}
/**********************End Of Product 服务层************************************/


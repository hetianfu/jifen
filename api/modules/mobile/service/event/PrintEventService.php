<?php

namespace api\modules\mobile\service\event;

use api\modules\mobile\models\event\OrderPayEvent;
use api\modules\mobile\service\BasicService;
use fanyou\service\PrintService;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-04-23
 */
class PrintEventService extends BasicService
{
    /*********************支付订单成功后，事件************************************/

    /**
     * 打印订单
     * @param OrderPayEvent $event
     */
    public static  function printAfterPay(OrderPayEvent  $event)
    {

        PrintService::printOrder($event->orderInfo);

    }

}
/**********************End Of Coupon 服务层************************************/


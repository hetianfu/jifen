<?php


namespace fanyou\error\errorCode;

/**
 * Class ErrorOrder
 * @package fanyou\error\errorCode
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-06-19 9:59
 */
class ErrorOrder
{
    const COUPON_HAS_GONE= '优惠券已领完';
    const COUPON_HAS_REPEAT= '优惠券只能领取一次';
    const COUPON_HAS_OUT_TIME= '优惠券已过期';
    const COUPON_UN_EFFECT = '优惠券不可用';

    const SCORE_UN_EFFECT ='用户积分不可用';
    const PAGE_OUT_TIME ='页面过期，重新下单';
    const FAIL_SUBMIT_ORDER ='下单失败，请重新选购';

    const NO_ORDER_SUBMIT= '商品为空，无法下单';

    const ORDER_HAD_PAID ='订单已支付';
    const ORDER_PAY_OUT_TIME ='订单支付超时';
    const ORDER_CAN_NOT_REFUND ='订单不能退款';
    const NO_POWER_TO_REFUND ='没有操作权限';



   const M_ORDER_CAN_NOT_REFUND ='订单不能退款';

    const M_ORDER_REFUND_OVER_LINE ='退款金额大于支付金额';
}
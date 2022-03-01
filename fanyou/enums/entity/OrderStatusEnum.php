<?php

namespace fanyou\enums\entity;

use fanyou\enums\BaseEnum;
use fanyou\enums\QueryEnum;

/**
 * Class OrderStatusEnum
 * @package fanyou\enums\entity
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-06-19 8:48
 */
class OrderStatusEnum extends BaseEnum
{

    const PINK_TYPE = 'pink';
    const SCORE_TYPE = 'score';
    const SEC_KILL_TYPE = 'strategy';


    const INIT = 'init';
    const UNPAID = 'unpaid';
    const PAYING = 'paying';
    const PAID = 'paid';
    const UN_SEND = 'unsend';

    const PINKING = 'pinking';
    const PINKED = 'pinked';
    const UN_CHECK = 'uncheck';
    const SENDING = 'sending';
    const UN_REPLY = 'unreply';
    const CLOSED = 'closed';


    const CANCELLED = 'cancelled';

    const REFUND = 'refund';
    const REFUNDING = 'refunding';

    const ALL_EFFECT_STATUS = 'effect';

    const effect=self::PAID . ',' . self::UN_SEND . ',' . self::SENDING . ',' .  self::CLOSED . ',' . self::UN_CHECK . ',' . self::SENDING . ',' . self::UN_REPLY;

    const EFFECT_ALL = QueryEnum::IN .self::effect ;

    const EFFECT_ARRAY =  [self::PINKING,  self::PAID, self::UN_SEND, self::SENDING,
        self::REFUNDING, self::CLOSED, self::UN_CHECK, self::SENDING, self::UN_REPLY];


    const DISTRIBUTE = 'distribute';
    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::INIT => '初使化',
            self::UNPAID => '待支付',

            self::UN_SEND => '待发货',
            self::UN_CHECK => '待核销',
            self::PAID => '已支付',
            self::CLOSED => '完成',
            self::SENDING => '待收货',

            self::UN_REPLY => '待评论',
            self::CANCELLED => '已取消',
            self::REFUND => '已退单',
            self::REFUNDING => '申请退款',

        ];
    }

    public static function countStatusInit(): array
    {
        return [

            self::INIT => 0,
            self::UNPAID => 0,
            self::PAID => 0,
            self::UN_SEND => 0,
            self::UN_CHECK => 0,
            self::SENDING => 0,
            self::ALL_EFFECT_STATUS=>0,

           // self::UN_REPLY => 0,
            self::CLOSED => 0,
            self::CANCELLED => 0,
            self::REFUND => 0,
            self::REFUNDING => 0,
        ];
    }

    public static function countPayTypeInit(): array
    {
        return [
            PayStatusEnum::WX => 0,
            PayStatusEnum::ALI_PAY => 0,
            PayStatusEnum::WALLET => 0,
        ];
    }


}
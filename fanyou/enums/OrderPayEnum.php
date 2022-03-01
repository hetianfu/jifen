<?php

namespace fanyou\enums;

use Yii;

/**
 * Class OrderPayEnum
 * @package fanyou\enums
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-07-06 15:24
 */
class OrderPayEnum
{
    const WX_JS_SDK = 'WX_JS_SDK';
    const PRE_ORDER = 'PRE_ORDER';
    const CALCULATE_ORDER = 'CALCULATE_ORDER';
    const CALCULATE_ORDER_TIME = 600;

    const ZERO_AMOUNT = 0;
    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::WX_JS_SDK => '微信JS_SDK',
            self::PRE_ORDER => '预下单',
            self::CALCULATE_ORDER => '计算订单价格',
            self::CALCULATE_ORDER_TIME => '计算订单价格的缓存时间',
            self::ZERO_AMOUNT=>'价格为0'
        ];
    }
}
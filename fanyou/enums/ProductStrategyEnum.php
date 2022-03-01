<?php

namespace fanyou\enums;

/**
 * 商品活动策略类型
 * Class ProductStrategyEnum
 * @package fanyou\enums
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-07-06 15:24
 */
class ProductStrategyEnum
{

    const SEC_KILL = 'SECKILL';
    const NUMBER = 'NUMBER';
    const GROUP_BUY = 'GROUP_BUY';
    public static function getMap(): array
    {
        return [
            self::SEC_KILL => '秒杀',
            self::NUMBER => '限量销售',
            self::GROUP_BUY => '团购',

        ];
    }

}
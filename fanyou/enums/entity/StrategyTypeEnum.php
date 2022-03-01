<?php

namespace fanyou\enums\entity;

use fanyou\enums\BaseEnum;

/**
 * Class StrategyTypeEnum
 * @package fanyou\enums\entity
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-06-19 8:48
 */
class StrategyTypeEnum extends BaseEnum
{


    const NORMAL = 'NORMAL';
    const SECKILL = 'SECKILL';
    const NUMBER = 'NUMBER';
    const TIMER='TIMER';
    const SALES = 'SALES';
    const SCORE = 'SCORE';

    const PINK = 'PINK';
    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::NORMAL=>'普通',
            self::SECKILL => '秒杀',
            self::NUMBER => '限购',
            self::TIMER=> '限时',
            self::SALES => '促销',

        ];
    }
}
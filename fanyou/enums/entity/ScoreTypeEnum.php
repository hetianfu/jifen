<?php

namespace fanyou\enums\entity;


/**
 * Class ShareTypeEnum
 * @package fanyou\enums\entity
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020/6/1 0001 : 14:21
 */
class ScoreTypeEnum
{
    const ORDER = 'ORDER';
    const SIGN = 'SIGN';
    const DEDUCT = 'DEDUCT';

    const REFUND = 'REFUND';

    const REPEAT_SCORE_MODE= 0;
    const MAX_SCORE_MODE = 1;
    /**
     * @return array
     */
    protected static function getMap(): array
    {
        return [

            self::ORDER => '消费赠送',
            self::SIGN => '签到领取',
            self::DEDUCT => '积分抵扣',


        ];
    }

}
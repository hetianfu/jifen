<?php

namespace fanyou\enums;

/**
 * 状态枚举
 * Class SystemArrayEnum
 * @package fanyou\enums
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-07-06 15:25
 */
class SystemArrayEnum extends BaseEnum
{
    const SCORE_SING_NUMBER = 'number';


    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::SCORE_SING_NUMBER => '积分签到值',

        ];
    }
}
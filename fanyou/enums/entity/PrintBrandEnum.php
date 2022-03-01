<?php

namespace fanyou\enums\entity;


/**
 * 打印机品牌枚举
 * Class PrintBrandEnum
 * @package fanyou\enums\entity
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-06-11 9:31
 */
class PrintBrandEnum
{
    const FEI_E_YUN = 'feieyun';
    const Y_L_YUN = 'ylyun';
    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::FEI_E_YUN => '飞鹅云',
            self::Y_L_YUN => '易联云',

        ];
    }
}
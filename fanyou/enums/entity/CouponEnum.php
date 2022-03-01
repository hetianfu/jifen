<?php

namespace fanyou\enums\entity;

use fanyou\enums\BaseEnum;

/**
 * Class CouponEnum
 * @package fanyou\enums\entity
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-06-20 16:36
 */
class CouponEnum extends BaseEnum
{
    const COMMON = 'COMMON';
    const PRODUCT = 'PRODUCT';
    const CATEGORY = 'CATEGORY';

    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::COMMON => '通用类',
            self::PRODUCT => '商品券',
            self::CATEGORY => '分类券',

        ];
    }
}
<?php

namespace fanyou\enums\entity;


/**
 * Class SystemGroupEnum
 * @package fanyou\enums\entity
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020/6/1 0001 : 14:22
 */
class SystemGroupEnum
{
    const  PRODUCT_TYPE = 'PRODUCT';
    const  STRATEGY_TYPE = 'STRATEGY';
    const  PINK_TYPE = 'PINK';
    const NORMAL_TYPE = 'NORMAL';
    /**
     * @return array
     */
    protected static function getMap(): array
    {
        return [

            self::PRODUCT_TYPE => '商品',
            self::STRATEGY_TYPE => '活动',
            self::NORMAL_TYPE => '参数',
        ];
    }

}
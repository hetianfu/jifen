<?php

namespace fanyou\enums\entity;


/**
 * Class ShareTypeEnum
 * @package fanyou\enums\entity
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020/6/1 0001 : 14:21
 */
class ShareTypeEnum
{
    const PRODUCT = 'GOODS_INFO';
    const USER = 'USER_INFO';
    const NEWS = 'NEWS';
    const SHOP_INFO = 'SHOP-INFO';

    /**
     * @return array
     */
    protected static function getMap(): array
    {
        return [

            self::PRODUCT => '商品分享',
            self::NEWS => '新闻',
            self::SHOP_INFO => '店铺分享',


        ];
    }

}
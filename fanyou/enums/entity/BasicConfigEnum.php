<?php

namespace fanyou\enums\entity;


/**
 * Class BasicConfigEnum
 * @package fanyou\enums\entity
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-06-19 8:48
 */
class BasicConfigEnum
{
    const BASIC_SITE = 'basic_site';
    const SITE_NAME = 'site_name';
    const SITE_URL = 'site_url';
    const SITE_SERVICE_PHONE ='site_service_phone';
    const SITE_QQ ='site_qq';
    const BASIC_SHARE_IMG = 'share_img';
    const SHARE_IMG_PRICE = 'share_img_price_left';
    const  SHARE_IMG_PRICE_TOP = 'share_img_price_top';
    const SHARE_IMG_HEAD_LEFT = 'share_img_head_left';
    const  SHARE_IMG_HEAD_TOP = 'share_img_head_top';


    const SHARE_PARTNER = 'share_partner';



    /**
     * @return array
     */
    protected static function getMap(): array
    {
        return [

            self::BASIC_SITE => '域名',
            self::SITE_NAME => '网站名称',
            self::SITE_URL => '网站地址',
            self::SITE_SERVICE_PHONE => '客服电话',

        ];
    }

}
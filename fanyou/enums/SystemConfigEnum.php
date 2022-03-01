<?php

namespace fanyou\enums;

use Yii;

/**
 * Class SystemConfigEnum
 * @package fanyou\enums\entity
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020/1/1   14:21
 */
class SystemConfigEnum
{
    const BASIC_CONFIG = 'basic_config';

    const WX_MP = 'weixin_mp';

    const WX_PAY = 'weixin_pay';

    const WX_MINI_APP = 'weixin_mini_app';

    const ALI_OSS = 'ali_oss';


    const INDEX_PAGE = 'index_page';

    const INDEX_APP_CONFIG = 'index_app_config';

    const MERCHANT = 'merchant';

    const SCORE_CONFIG = 'score';

    const NUMBER = 'number';

    const FREIGHT_CONFIG = 'freight';

    const DISTRIBUTE_CONFIG = 'distribute';

    const PRINT_CONFIG = 'print';
    const PRINT_YLY_CONFIG = 'yly_print';


    const ALI_PAY = 'ali_pay';
    const UNION_PAY = 'union_pay';

    const SYSTEM_SMS = 'system_sms';

    const BLACK_IP = 'black_ip';


    const API_99_KEY ='api_99_key';
    const REDIS_USER_INFO ='XF-user-info';

    const REDIS_COMMON_CONFIG ='html_common_config_';
    const REDIS_PAGE_TITLE ='html_page_title_';
    const REDIS_CHANNEL ='html_channel_';
    /**
     * @return array
     */
    protected static function getMap(): array
    {
        $userCache = Yii::$app->user->identity;
        $merchant_id =$userCache['id'];

        return [

            self::BASIC_CONFIG => '基础配置',

            self::WX_MP => '微信公众号',
            self::WX_PAY => '微信支付',
            self::WX_MINI_APP => '微信小程序',

            self::INDEX_PAGE => '移动端首页配置',
            self::INDEX_APP_CONFIG => '移动端小程序配置',

            self::FREIGHT_CONFIG => '快递配置',
            self::SCORE_CONFIG => '积分配置',
            self::PRINT_CONFIG => '打印机配置',



        ];
    }

    /**
     * @param $key
     * @param integer $type
     * @param string $prefix
     * @return string
     */
    public static function getPrefix($key, $prefix = '')
    {
        if (empty($prefix)) {
        //    $prefix = static::getMap()[$key] ?? '';
        }
        return  $key ;
    }

}
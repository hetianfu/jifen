<?php

namespace fanyou\enums;

use Yii;

/**
 * Class CacheEnum
 * @package fanyou\enums
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-07-06 15:25
 */
class CacheEnum
{

    const WX_MP = 'weixin_mp';
    const WX_PAY = 'weixin_pay';
    const WX_MINI_APP = 'weixin_mini_app';

    const ALI_OSS = 'ali_oss';
    /**
     * @return array
     */
    protected static function getMap(): array
    {
        $userCache = Yii::$app->user->identity;
        $merchant_id =$userCache['id'];

        return [
            'config' => $merchant_id, // 公用参数

            'weixin_mp'=>$merchant_id , //公众号
            'weixin_pay'=>$merchant_id , //微信支付
            'weixin_mini_app'=>$merchant_id , //微信小程序

            'addonsConfig' => $merchant_id, // 插件配置
            'apiAccessToken' => $merchant_id, // 用户信息记录
            'wechatFansStat' => $merchant_id, // 粉丝统计缓存
            'addons' => '', // 插件
            'provinces' => '', // 省市区
            'ipBlacklist' => '', // ip黑名单
            'actionBehavior' => '', // 需要被记录的行为


        ];
    }

    /**
     * @param $key
     * @param integer $type
     * @param string $prefix
     * @return string
     */
    public static function getPrefix($key, $type=0, $prefix = '')
    {
        if (empty($prefix)) {
            $prefix = static::getMap()[$key] ?? '';
        }
        return  $key ; //$prefix .$type.
    }

}
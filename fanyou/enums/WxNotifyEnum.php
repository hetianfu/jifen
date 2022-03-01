<?php

namespace fanyou\enums;

/**
 * Class WxNotifyEnum
 * @package fanyou\enums
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-06-19 8:49
 */
class WxNotifyEnum
{
    const SUCCESS ='<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>';
    const FAIL ='<xml><return_code><![CDATA[FAIL]]></return_code><return_msg><![CDATA[FAIL]]></return_msg></xml>';

    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::SUCCESS => '微信回调成功',
            self::FAIL => '微信回调失败',

        ];
    }
}
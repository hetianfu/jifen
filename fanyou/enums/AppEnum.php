<?php

namespace fanyou\enums;

/**
 * Class AppEnum
 * @package fanyou\enums
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-06-19 8:47
 */
class AppEnum extends BaseEnum
{
    const MERCHANTID = '1';

    const QUEUE = 'queue';

    const GET_REQUEST = 'GET';

    const BACKEND = 'backend';

    const SELLER = 'seller';
    const MANAGE = 'manage';
    const MOBILE = 'mobile';

    const GLOBAL_NAME ="科技";
    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::MERCHANTID => '默认商户',

        ];
    }
}
<?php

namespace fanyou\enums\entity;


/**
 * Class PrintConfigEnum
 * @package fanyou\enums\entity
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-06-10 18:17
 */
class WechatConfigEnum
{
    const OAUTH_REDIRECT= 'oauth_redirect';

    /**
     * @return array
     */
    protected static function getMap(): array
    {
        return [
            self::OAUTH_REDIRECT => '微信网页授权回调地址',

        ];
    }

}
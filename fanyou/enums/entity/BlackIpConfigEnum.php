<?php

namespace fanyou\enums\entity;


/**
 * Class BlackIpConfigEnum
 * @package fanyou\enums\entity
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-06-19 8:48
 */
class BlackIpConfigEnum
{
    const IP_LIST = 'ip_list';
    const FORBID_SECONDS = 'forbid_seconds';
    const BLACK_OPEN = 'sys_black_open';

    /**
     * @return array
     */
    protected static function getMap(): array
    {
        return [
            self::IP_LIST => '黑名单列表',
            self::BLACK_OPEN => '开关',
            self::FORBID_SECONDS=>'禁用时间'
        ];
    }

}
<?php

namespace fanyou\device\enums;

/**
 * Class AppEnum
 * @package fanyou\enums
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-06-19 8:47
 */
class CommandTypeEnum
{
    const ADD = 'ADD';

    const CHARGE = 'CHARGE';
    const QUERY_ONLINE='QUERYONLINE';
    const QUERY_LIST='QUERYLIST';
    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::ADD => '添加设备',

        ];
    }
}
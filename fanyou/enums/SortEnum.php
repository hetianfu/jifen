<?php

namespace fanyou\enums;

/**
 * 状态枚举
 * Class SortEnum
 * @package fanyou\enums
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-01-06 15:24
 */
class SortEnum
{
    const SHOW_ORDER = 'show_order';
    const CREATED_AT = 'created_at';

    const ASC = 'asc';
    const DESC = 'desc';

    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::ASC => '启用',
            self::DESC => '禁用',

        ];
    }
}
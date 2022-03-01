<?php

namespace fanyou\enums;


/**
 * Class QueryEnum
 * @package fanyou\enums
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-07-06 15:24
 */
class QueryEnum extends BaseEnum
{
    const TOTAL_COUNT = 'totalCount';
    const PAGE = 'page';
    const LIMIT = 'limit';
    const IN = 'in';
    const NOT_IN = 'not_in';
    const IS_NULL = 'is_null';
    const GT = '>';
    const GE = '>=';
    const LT = '<';
    const LE = '<=';
    const  CREATE_AT = 'created_at';
    const  UPDATE_AT = 'updated_at';
    const LE_CREATE_AT = 'le_created_at';
    const GE_CREATE_AT = 'ge_created_at';
    const GT_CREATE_AT = 'gt_created_at';
    const GT_UPDATE_AT = 'gt_updated_at';
    const LE_UPDATE_AT = 'le_updated_at';
    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::TOTAL_COUNT => '查询总条数',
            self::PAGE => '当前页数',
            self::LIMIT => '每页条数',
            self::IN => 'in查询',
            self::GT => '大于',
            self::GE => '大于或等于',
            self::LT => '大于',
            self::LE => '小于或等于',
        ];
    }
}
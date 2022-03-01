<?php

namespace fanyou\enums\entity;


/**
 * Class DistributeConfigEnum
 * @package fanyou\enums\entity
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-06-19 8:48
 */
class DistributeConfigEnum
{
    const DISTRIBUTE_EVERY = 'is_every';
    const DISTRIBUTE_FIRST = 'first_percent';
    const DISTRIBUTE_SECOND = 'second_percent';

    const DISTRIBUTE_MIN_DRAW = 'min_draw';
    const DISTRIBUTE_STATUS = 'status';


    const FIRST_SHARED = 'first_shared';
    const SECOND_SHARED = 'second_shared';
    const THREE_SHARED = 'three_shared';

    const FIRST_TEAM = 'first_team';
    const SECOND_TEAM = 'second_team';

    const PROXY_FEE = 'proxy_fee';

    const CITY_PROXY = 3;
    const AREA_PROXY = 2;
    const RETAIL_PROXY = 1;

    /**
     * @return array
     */
    protected static function getMap(): array
    {
        return [

            self::DISTRIBUTE_EVERY => '是否人人分销',
            self::DISTRIBUTE_FIRST => '一级返佣比例',
            self::DISTRIBUTE_SECOND => '二级返佣比例',
            self::DISTRIBUTE_MIN_DRAW => '最低提现金额',

        ];
    }

}
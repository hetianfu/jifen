<?php

namespace fanyou\enums;


use fanyou\tools\DaysTimeHelper;

/**
 * Class DateQueryEnum
 * @package fanyou\enums
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-07-06 15:24
 */
class DateQueryEnum
{
    const YESTERDAY = 'yesterday';
    const TODAY = 'today';
    const WEEK = 'week';
    const SEVEN_DAY = 'seven_day';
    const MONTH = 'month';
    const THIRTY_DAY = 'thirty_day';

    const YEAR = 'year';
    public static function queryDayInit(): array
    {
        return [

            self::TODAY => DaysTimeHelper::today(),
            self::YESTERDAY => DaysTimeHelper::yesterday(),
            self::WEEK => DaysTimeHelper::thisWeek(),

            self::SEVEN_DAY => DaysTimeHelper::daysAgo(7),
            self::MONTH => DaysTimeHelper::thisMonth(),

            self::THIRTY_DAY => DaysTimeHelper::daysAgo(30),

            self::YEAR => DaysTimeHelper::thisYear(),
        ];
    }
}
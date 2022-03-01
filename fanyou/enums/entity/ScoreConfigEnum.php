<?php

namespace fanyou\enums\entity;


/**
 * Class ScoreConfigEnum
 * @package fanyou\enums\entity
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020/6/1   14:21
 */
class ScoreConfigEnum
{
    const SCORE_CONVERT = 'score_convert';
    const SCORE_DEDUCT = 'score_ratio';
    //签到积分 0-循环获取，1-最大值获取
    const SING_TYPE= 'sign_type';
    const SIGN_LIST= 'sign_list';

    const SIGN_MIN_LINE = 'sign_min_line';
    const SIGN_MAX_LINE= 'sign_max_line';

    const SIGN_CONFIG= 'sign_config';

    const PRESENT_TYP= 'present_type';

    const PRESENT_RATIO= 'present_ratio';
    /**
     * @return array
     */
    protected static function getMap(): array
    {
        return [
            self::SCORE_DEDUCT => '积分抵扣比例',
            self::SIGN_MAX_LINE => '积分最大上限',
            self::SING_TYPE => '签到获取积分类型',
            self::PRESENT_TYP => '赠送积分类型',
            self::PRESENT_RATIO => '赠送积分比例',
        ];
    }

}
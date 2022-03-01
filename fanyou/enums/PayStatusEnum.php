<?php

namespace fanyou\enums;


/**
 * Class DrawStatusEnum
 * @package fanyou\enums\entity
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020/6/1  : 14:22
 */
class PayStatusEnum
{
    const WX = 'wx';
    const WX_MP = 'wx_mp';
    const CASH = 'cash';
    const BANK_CARD = 'bank';
    const ALI_PAY='alipay';
    const WALLET='wallet';
    const SCORE='score';

    const AFTER = 'after';

    public static function allType(): array
    {
        return [
            self::WX  ,
            self::WALLET  ,
            self::ALI_PAY ,
            self::AFTER
        ];
    }

    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [

            self::WX => '微信',
            self::WALLET => '钱包',
            self::ALI_PAY => '支付宝',
            self::AFTER => '货到付款',
        ];
    }
    public static  function countPayStatusInit(): array
    {
        return [
            self::WX=>0,
            self::CASH=>0,
            self::ALI_PAY=>0,
            self::AFTER=>0,
            self::WALLET=>0,
            self::SCORE=>0,
        ];
    }

}
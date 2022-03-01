<?php

namespace fanyou\enums;


/**
 * Class WalletStatusEnum
 * @package fanyou\enums\entity
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-06-01 15:03
 */
class WalletStatusEnum extends BaseEnum
{
    //公众号提现
    const MP_DRAW = 'MP_DRAW';

    const DRAW = 'DRAW';
    const DISTRIBUTE = 'DISTRIBUTE';
    const CONSUME='CONSUME';
    const CHARGE='CHARGE';
    const TEAM = 'TEAM';
    const PROXY = 'PROXY';

    const VIP='VIP';
    const REFUND='REFUND';

    const MP_DT = 'MP_DT';
    const MP_REFUND = 'MP_REFUND';

    const EFFECT_IN=WalletStatusEnum::CONSUME.','.WalletStatusEnum::REFUND.','.WalletStatusEnum::TEAM.','.WalletStatusEnum::PROXY.','.WalletStatusEnum::DISTRIBUTE;

    const FINAN_EFFECT_IN= WalletStatusEnum::MP_DT.','.WalletStatusEnum::MP_REFUND.','
            .WalletStatusEnum::CONSUME.','.WalletStatusEnum::REFUND.','
         .WalletStatusEnum::TEAM.','.WalletStatusEnum::PROXY.','.WalletStatusEnum::DISTRIBUTE;

    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::DRAW => '提现',

            self::DISTRIBUTE => '分销',
            self::CONSUME => '消费',
            self::CHARGE => '充值',
            self::REFUND => '退单',
            self::VIP => '充值VIP',
            self::MP_DT => '公众号分销',
            self::MP_REFUND => '退单时，公众号退还',
        ];
    }
    public static function getDescribe($status)
    {
        return self::getMap()[$status];

    }
}
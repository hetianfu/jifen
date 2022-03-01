<?php

namespace fanyou\enums\entity;


/**
 * Class FreightConfigEnum
 * @package fanyou\enums\entity
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-06-01 : 14:27
 */
class FreightConfigEnum
{
    const FREIGHT_FREE_LINE = 'freight_free';
    const FREIGHT_AMOUNT = 'freight_amount';

    /**
     * @return array
     */
    protected static function getMap(): array
    {
        return [

            self::FREIGHT_FREE_LINE => '商户满额包邮',
            self::FREIGHT_AMOUNT => '商户邮费',

        ];
    }

}
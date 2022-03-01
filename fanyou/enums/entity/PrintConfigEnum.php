<?php

namespace fanyou\enums\entity;


/**
 * Class PrintConfigEnum
 * @package fanyou\enums\entity
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-06-10 18:17
 */
class PrintConfigEnum
{
    const ACCOUNT_ID= 'account_id';
    const ACCOUNT_SECRET = 'account_secret';

    const PRINT_BRAND = 'print_brand';
    const PRINT_KEY = 'print_key';
    const PRINT_SN = 'print_sn';

    const PRINT_TITLE = 'print_title';
    const PRINT_LOGO = 'print_logo';


    const PRINT_NAME = 'name';
    const REMARK = 'remark';

    const AUTH_CODE = 'code';

    const CONTENT = 'content';

    const PRINT_AFTER_PAY= 'print_after_pay';
    const PRINT_APP_ID= 'print_app_id';




    /**
     * @return array
     */
    protected static function getMap(): array
    {
        return [
            self::PRINT_BRAND => '打印机品牌',
            self::ACCOUNT_ID => '打印机开发者帐号',
            self::ACCOUNT_SECRET => '打印机平台KEY',

            self::PRINT_AFTER_PAY => '支付后自动打印',
            self::PRINT_APP_ID=>'打印机应用Id',
            self::PRINT_KEY=>'打印机KEY',

            self::CONTENT => '打印内容',

            self::PRINT_SN => '打印机SN',
            self::REMARK => '备注',
        ];
    }

}
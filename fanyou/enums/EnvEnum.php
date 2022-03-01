<?php

namespace fanyou\enums;

use fanyou\error\FanYouHttpException;
use Yii;
use yii\base\Exception;
use yii\db\Connection;

/**
 * Class OrderPayEnum
 * @package fanyou\enums
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-07-06 15:24
 */
class EnvEnum
{


    const ADMIN_NAME = 'ADMIN_NAME';
    const ADMIN_LOGO = 'ADMIN_LOGO';
    const ADMIN_VERSION = 'ADMIN_VERSION';


    const DB_DSN = 'DB_DSN';
    const DB_NAME = 'DB_NAME';
    const DB_USERNAME = 'DB_USERNAME';
    const DB_PASSWORD = 'DB_PASSWORD';

    const DB_ENABLE_CACHE = 'DB_ENABLE_CACHE';
    const DB_CACHE_TIME = 'DB_CACHE_TIME';

    const STORE_IMAGE_DRIVE = 'STORE_IMAGE_DRIVE';

    const STORE_IMAGE_DRIVE_DESC = "阿里云：快速、稳定、易转移。需配置阿里云存储;本地存储：简单、慢、可靠性差，难转移。";
    public static function getMap(): array
    {
        return [  self::DB_DSN=>'主机地址',  self::DB_NAME=>'数据库名' , self::DB_USERNAME=>'用户名', self::DB_PASSWORD=>'密码',
                  self::DB_ENABLE_CACHE=>'数据库缓存',    self::DB_CACHE_TIME =>'缓存时间(秒)', self::STORE_IMAGE_DRIVE =>'存储驱动类型',
        ];
    }
    public static function getAdminConfig(): array
    {
       return [  EnvEnum::ADMIN_NAME=> $_ENV[EnvEnum::ADMIN_NAME], EnvEnum::ADMIN_LOGO=>$_ENV[EnvEnum::ADMIN_LOGO] , EnvEnum::ADMIN_VERSION=>$_ENV[EnvEnum::ADMIN_VERSION]];

    }
}
<?php

namespace fanyou\enums\entity;

/**
 * Class MiniProgramEnum
 * @package fanyou\enums\entity
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-06-20 16:08
 */
class MiniProgramEnum
{
    const CODE = 'code';
    const CODE2SESSION = 'codeToSessionKey';
    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::CODE => '小程序前端传入的CODE',
            self::CODE2SESSION => '小程序code换取session',


        ];
    }
}
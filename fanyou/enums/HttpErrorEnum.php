<?php

namespace fanyou\enums;

/**
 * 状态枚举
 * Class HttpErrorEnum
 * @package fanyou\enums
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-07-06 15:24
 */
class HttpErrorEnum
{
    const REDIRECT=301;
    const UNAUTHORIZED = 401;
    const Expectation_Failed = 417;
    const Unprocessable_Entity = 422;
    const Locked=423;
    const Unavailable_For_Legal_Reasons=451;
    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [

            self::UNAUTHORIZED=>'用户没有访问权限',
            self::Locked=>'当前资源被锁定',
            self::Unprocessable_Entity=>'请求格式正确，但是由于含有语义错误，无法响应',
            self::Expectation_Failed=>'在请求头 Expect 中指定的预期内容无法被服务器满足，或者这个服务器是一个代理服务器',

            self::Unavailable_For_Legal_Reasons=> '该请求因法律原因不可用'
        ];
    }
}
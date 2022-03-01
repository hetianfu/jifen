<?php

namespace fanyou\enums;

/**
 * 状态枚举
 * Class StatusEnum
 * @package fanyou\enums
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-01-29 18:08
 */
class StatusEnum
{
    const ENABLED = 1;
    const DISABLED = 0;
    const DELETE = -1;

    const OUT_STATUS = -1;

    const SYSTEM = 0;
    const APP = 1;
    const GROUP = 2;

    const SUCCESS = 1;
    const FAIL = 0;

    const STATUS_INIT = 0;

    const EXPIRE = -1;
    const UN_USED = 0;
    const USED = 1;

    const COME_IN = 1;
    const COME_OUT = -1;


    const UN_SUBMIT = -2;
    const PENDING =0;
    const APPROVE = 1;
    const FORBID = -1;

    const S_SUCCESS = "1";
    const S_FAIL = "0";

}
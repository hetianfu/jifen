<?php


namespace fanyou\error\errorCode;

/**
 * Class ErrorUser
 * @package fanyou\error\errorCode
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-06-19 9:59
 */
class ErrorUser
{
    const USER_NO_POWER= '无操作权限！';
    const USER_LOGIN_ERROR= '帐号或密码错误';

    const USER_RE_LOGIN= '请重新登陆';
    const USER_UN_LEGAL= '用户不合法';

    const USER_HAS_EXISTS= '用户已存在';

   const USER_HAD_PARENT ='用户已有上家';

    const USER_ALREADY_SING ='今日已签到';

    const USER_WALLET_LESS= '用户余额不足';

    const USER_REPEAT_DRAW= '已有提现申请';
    const MIN_DRAW_CASH= '最低提现';
}
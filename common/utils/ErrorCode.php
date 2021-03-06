<?php


namespace common\utils;

class ErrorCode {
    public static $errCode=array(
        '400'=>'错误请求',
        '401'=>'未授权',
        '403'=>'余额不足',
        '404'=>'请求地址错误',
        '405'=>'请求迷路了，请重试',
        '406'=>'不接受',
        '407'=>'需要代理授权',
        '408'=>'请求超时',
        '409'=>'请求冲突',
        '410'=>'已删除',
        '411'=>'需要有效长度',
        '412'=>'未满足前提条件',
        '413'=>'请求实体过大',
        '414'=>'URL过长',
        '415'=>'不支持的媒体类型',
        '416'=>'请求范围不符合要求',
        '417'=>'参数错误',
        '423'=>'安全码错误',
        '418'=>'重复提交',
        '419'=>'未实名认证',
        '428'=>'用户已注册',
        '427'=>'用户格子已满',
        '420'=>'添加失败',
        '421'=>"资源不存在",
        '422'=>"操作失败",
        '426'=>"请联系管理员",
        '500'=>'内部错误',
        '501'=>'无法识别',
        '503'=>'服务不可用',
        '510'=>'微信平台余额不足',
        '424'=>'帐号或密码错误',
        '425'=>'资源不存在',
        '429'=>'该用户已有上级',
        '430'=>'预定失败',
        '431'=>'台桌不存在',
        '432'=>'台桌已开台',
        '433'=>'没有可选择的店铺',
        '435'=>'员工不存在',
        '436'=>'部门不存在或已删除',
        '940201' => '锁定失败',
        '940202' => '库存锁定，禁止操作',
        '940203' => '操作失败，请锁定库存',
        '940205' => '解锁失败',
        // 众筹模块错误
        '82001' => '众筹状态无法更新',
        '82002' => '众筹无法购买',
        // 商品模块错误
        '9154318' => '商品名重复',
        '9154319' => '商品存在子商品',
        '9154320' => '条形码重复',
        '9154417' => '商品校验不通过',
        '9154401' => '权限不足',
        '9154417' => '校验不通过',
        '9154418' => '单位已存在',
        '9154419' => '单位下存在商品',
        '9154517' => '分类校验不通过',
        '9154518' => '分类已存在',
        '9154519' => '分类下存在子分类',
        '9154520' => '分类下有商品',
        // 店铺部门
        '9154612' => '部门名称不能重复',
        // 支付方式模块错误
        '9154628' => '支付方式名称重复',

        '9160610'=>'台桌不存在',
        '9160612'=>'台桌名称重复',
        '9160620'=>'区域不存在',
        '9160628'=>'区域名称重复',
        '9160630'=>'区域存在子台桌',

        // 店铺用户
        '9154818'=>'店铺用户存在',


    );

    public static function getErrText($err) {
        if (isset(self::$errCode[$err])) {
            return self::$errCode[$err];
        }else {
            return "错误未定义";
        };
    }
}

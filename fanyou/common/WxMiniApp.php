<?php

namespace fanyou\common;

use yii\base\Model;

/**
 * Class WxMiniApp
 * @package fanyou\common
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-06-10 15:00
 */
class WxMiniApp extends Model
{
    public $app_id;
    public $secret;
    public $response_type='array';
    public $log=[  'level' => 'info',  'file' =>__DIR__ . '/../../api/runtime/logs/wechat.log', ];
}
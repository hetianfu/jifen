<?php

namespace fanyou\common;

use yii\base\Model;

/**
 * Class WxPayment
 * @package fanyou\common
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-06-10 14:59
 */
class WxPayment extends Model
{
    public $app_id;
    public $mch_id;
    public $key;
    public $cert_path;
    public $key_path;

    public $notify_url;

    public $sub_appid;
    public $sub_mch_id;

}
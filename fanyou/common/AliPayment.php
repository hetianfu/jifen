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
class AliPayment extends Model
{
    public $union_mchid;
    public $alipay_cert_path;

    public $alipay_key_path;
    public $union_cert_id;

    public $union_private_key;

}
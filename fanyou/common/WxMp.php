<?php

namespace fanyou\common;

use yii\base\Model;

/**
 * Class WxMp
 * @package fanyou\common
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-06-10 15:00
 */
class WxMp extends Model
{
    public $app_id;
    public $secret;
    public $token;
    public $aes_key;

    public $oauth_redirect;

    public $response_type='array';

}
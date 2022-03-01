<?php

namespace fanyou\common;

use yii\base\Model;

/**
 * Class AliOssModel
 * @package fanyou\common
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-06-10 15:00
 */
class AliOssModel extends Model
{
    public $access_key_id;
    public $access_key_secret;
    public $bucket_name;

    public $endpoint;
    public $user_url;
}
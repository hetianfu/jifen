<?php

namespace fanyou\common;

use yii\base\Model;

/**
 * Class GroupConfigValue
 * @package fanyou\common
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-06-10 15:00
 */
class GroupConfigValue extends Model
{
    //public $name;
    //public $title;
    public $sort;
    public $value;
    public function fields(){
        $fields=parent::fields();
        return $fields;
    }

}
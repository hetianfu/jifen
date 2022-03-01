<?php

namespace fanyou\common;

use yii\base\Model;

/**
 * Class StockOrder
 * @property mixed skuId
 * @property mixed specSnap
 * @package fanyou\common
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-06-10 15:00
 */
class StockOrder extends Model
{
    public $id;
    public $skuId;
    public $stockId;
    public $name;
    public $number;
    public $strategyType;
    public $amount;
    public $costAmount;

    public $specSnap;
    public function fields(){
        $fields=parent::fields();
        return $fields;
    }

}
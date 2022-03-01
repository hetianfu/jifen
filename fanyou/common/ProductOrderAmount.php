<?php

namespace fanyou\common;

use yii\base\Model;

/**
 * 当前订单商品的价格
 * Class ProductOrderAmount
 * @package fanyou\common
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-06-10 15:00
 */
class ProductOrderAmount extends Model
{
    public $product_id;

    public $category_id;
    public $is_sku;
    public $sku_id;

    public $origin_price=0;
    public $member_price=0;
    public $sale_price=0;

    public $stock_number=0;

    public $first_shared=0;
    public $second_shared=0;
    public $spec_snap;

}
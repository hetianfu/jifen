<?php
namespace fanyou\common;
use yii\base\Model;

class FormatProductResult extends Model{
    public $id;
    public $name;
    public $pic;
    public $url;
    public $strategy_type;
    public $cover_img;
    public $sale_price;
    public $origin_price;
    public $sales_number;
    public $effect_time;
    public $expire_time;
    public $status;
    public $is_on_sale;

    public $sale_strategy;
    public $on_show;
    public $start_seconds;
    public $end_seconds;
    public $images;

    public $start_time;
    public $end_time;
    public $people;

    public $base_sales_number;
    public function fields(){
        $fields=parent::fields();

        return $fields;
    }

}


<?php
namespace api\modules\mobile\models;
use yii\base\Model;

/**
 * This is the model class for table "{{%basic_order_info}}".
 *
 * @property  discountAmount
 */
class CartSkuResult extends Model {
    public $id;

    public $stock_number;
    public $name;
    public $images;
    public $sale_price;
    public $member_price;
    public $origin_price;
    public $spec_snap;
    public function fields(){
        $fields = parent::fields();

        return $fields;
    }

    public function setAttributes($values, $safeOnly = true) {

        parent::setAttributes($values, $safeOnly);
    }
}


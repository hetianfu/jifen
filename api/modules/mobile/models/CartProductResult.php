<?php
namespace api\modules\mobile\models;
use yii\base\Model;

/**
 * This is the model class for table "{{%basic_order_info}}".
 *
 * @property  discountAmount
 */
class CartProductResult extends Model {

    public function __construct($model)
    {
        $skuModel = new CartSkuResult();
        $skuModel->setAttributes($model, false);
        $this->skuInfo = $skuModel->toArray();
    }

    public $id;
    public $is_sku;
    public $is_on_sale;

    public $member_price;
    public $origin_price;
    public $sale_price;
    public $name;
    public $images;
    public $skuInfo;

    public function fields(){
        $fields = parent::fields();
        $fields['member_price']=function (){
            $member_price= $this->skuInfo['member_price'];
            return $member_price;
        };
        $fields['origin_price']=function (){
            return $this->skuInfo['origin_price'];
        };
        $fields['sale_price']=function (){
            return $this->skuInfo['sale_price'];
        };
        if(!$this->is_sku){
            unset($fields['skuInfo']);
        }

        return $fields;
    }

}


<?php
namespace api\modules\seller\models\forms;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-05-08
 */

class ProductStockDetailQuery  extends BaseQuery{
     public  $name;
    public  $sub_type;
    public  $type;
    public  $sku_id;

    public  $product_id;
     public function rules(){
         $rules = parent::rules();
         return array_merge([
             [['name' ,'sku_id'], 'string'],
             [['sub_type','type' ], 'integer'],
             [['product_id'], 'safe'],
            ], $rules);
         }
    public function fields(){
        $fields = parent::fields();
        isset($this->sub_type)&&$fields['sub_type']='sub_type';
        isset($this->type)&&$fields['type']='type';
        isset($this->sku_id)&&$fields['sku_id']='sku_id';
        isset($this->product_id)&&$fields['product_id']='product_id';
        return $fields;
    }
}
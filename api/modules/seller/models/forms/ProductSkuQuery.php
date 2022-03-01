<?php
namespace api\modules\seller\models\forms;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-03-26
 */

class ProductSkuQuery  extends BaseQuery{
     public $name;
     public $product_id;
     public function rules(){
         $rules = parent::rules();
         return array_merge([
                [['name','productId' ], 'string'],
            ], $rules);
         }
    public function fields(){
        $fields  = parent::fields();
        isset($this->product_id) &&$fields['product_id'] = 'product_id';
        isset($this->name) &&$fields['name'] = 'name';
        return $fields;
    }


}
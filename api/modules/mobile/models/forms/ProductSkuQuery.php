<?php
namespace api\modules\mobile\models\forms;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-03-26
 */

class ProductSkuQuery  extends BaseQuery{
     public $name;
     public $productId;
     public function rules(){
         $rules = parent::rules();
         return array_merge([
                [['name','productId' ], 'string'],
            ], $rules);
         }
    public function fields(){
        $fields  = [];
        $this->productId &&$fields['product_id'] = 'productId';
        $this->name &&$fields['name'] = 'name';
        unset($fields['page'],$fields['limit'] ,$fields['productId']);
        return $fields;
    }


}
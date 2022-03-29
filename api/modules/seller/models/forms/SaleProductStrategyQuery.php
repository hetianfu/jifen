<?php
namespace api\modules\seller\models\forms;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-05-07
 */

class SaleProductStrategyQuery  extends BaseQuery{
     public  $name;
     public  $product_id;
     public function rules(){
         $rules = parent::rules();
         return array_merge([
                [['name','product_id' ], 'string'],
            ], $rules);
         }
    public function fields(){
        $fields = parent::fields();
        $this->product_id&&$fields['product_id']='product_id';
        return $fields;
    }
}
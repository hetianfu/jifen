<?php
namespace api\modules\seller\models\forms;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-06-09
 */

class ProductCategoryQuery  extends BaseQuery{
    public  $product_id;
    public  $category_id;
     public function rules(){
         $rules = parent::rules();
         return array_merge([
                [['category_id' ], 'string'],
                [['product_id' ], 'string'],
            ], $rules);
         }
    public function fields(){
        $fields = parent::fields();
        isset($this->product_id)&&$fields['product_id']=   'product_id';
        isset($this->category_id)&&$fields['category_id']=   'category_id';
        return $fields;
    }
}
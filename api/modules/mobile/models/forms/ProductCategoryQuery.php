<?php
namespace api\modules\mobile\models\forms;

/**
 * Class ProductCategoryQuery
 * @package api\modules\mobile\models\forms
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-06-10 14:33
 */

class ProductCategoryQuery  extends BaseQuery{
    public  $product_id;
    public  $category_id;
     public function rules(){
         $rules = parent::rules();
         return array_merge([
                [['category_id' ], 'string'],
            ], $rules);
         }
    public function fields(){
        $fields = parent::fields();
        isset($this->category_id)&&$fields['category_id']=   'category_id';
        return $fields;
    }

}
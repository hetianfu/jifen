<?php
namespace api\modules\mobile\models\forms;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-05-21
 */

class UserProductQuery  extends BaseQuery{
     public  $name;
     public  $product_id;
     public function rules(){
         $rules = parent::rules();
         return array_merge([
                [['name' ], 'string'],
             [['product_id' ], 'string'],
            ], $rules);
         }
    public function fields(){
        $fields = parent::fields();
        isset($this->user_id)&&$fields['user_id']='user_id';
        isset($this->product_id)&&$fields['product_id']='product_id';
        return $fields;
    }
}
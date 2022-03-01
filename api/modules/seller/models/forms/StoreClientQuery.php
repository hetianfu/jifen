<?php
namespace api\modules\seller\models\forms;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-06-13
 */

class StoreClientQuery  extends BaseQuery{
     public  $name;
    public  $category_id;
     public function rules(){
         $rules = parent::rules();
         return array_merge([
                [['name' ], 'string'],
             [['category_id' ], 'integer'],
            ], $rules);
         }
    public function fields(){
        $fields = parent::fields();
        isset( $this->name)&& $fields['name']='name';
        isset($this->category_id)&& $fields['category_id']='category_id';
        return $fields;
    }
}
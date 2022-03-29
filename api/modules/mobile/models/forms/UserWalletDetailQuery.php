<?php
namespace api\modules\mobile\models\forms;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-05-22
 */

class UserWalletDetailQuery  extends BaseQuery{
     public  $name;
     public  $type;
     public  $sub_type;
     public function rules(){
         $rules = parent::rules();
         return array_merge([
             [['name' ,'type'], 'string'],
             [['sub_type' ], 'integer'],
            ], $rules);
         }
    public function fields(){
        $fields = parent::fields();
        isset($this->type)&&$fields['type']='type';
        isset($this->sub_type)&&$fields['sub_type']='sub_type';
        return $fields;
    }
}
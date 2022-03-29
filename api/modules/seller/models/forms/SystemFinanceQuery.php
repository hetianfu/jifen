<?php
namespace api\modules\seller\models\forms;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-06-16
 */

class SystemFinanceQuery  extends BaseQuery{
     public  $name;
     public  $type;
     public  $user_id;

     public function rules(){
         $rules = parent::rules();
         return array_merge([
                [['name','type','user_id' ], 'string'],
            ], $rules);
         }
    public function fields(){
        $fields = parent::fields();
        isset($this->type)&&$fields['type']='type';
        isset($this->user_id)&&$fields['user_id']='user_id';
        return $fields;
    }
}
<?php
namespace api\modules\mobile\models\forms;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-06-16
 */

class UserVipDetailQuery  extends BaseQuery{
     public  $user_id;
     public  $name;
     public function rules(){
         $rules = parent::rules();
         return array_merge([
                [['user_id','name' ], 'string'],
            ], $rules);
         }
    public function fields(){
        $fields = parent::fields();
        isset($this->user_id)&&$fields['user_id']='user_id';
        return $fields;
    }
}
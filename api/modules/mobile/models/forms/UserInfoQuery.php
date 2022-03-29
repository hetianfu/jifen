<?php
namespace api\modules\mobile\models\forms;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-04-23
 */

class UserInfoQuery  extends BaseQuery{
     public  $status;
     public  $parent_id;
     public function rules(){
         $rules = parent::rules();
         return array_merge([
                [['status' ,'parent_id'], 'string'],
            ], $rules);
         }
    public function fields(){
            $fields =parent::fields();
            isset($this->status)&&$fields['status']='status';
            isset($this->parent_id)&&$fields['parent_id']='parent_id';
        return $fields;
    }

}
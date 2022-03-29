<?php
namespace api\modules\seller\models\forms;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-04-23
 */

class UserScoreDetailQuery  extends BaseQuery{
     public  $name;
    public  $user_id;
    public  $type;
     public function rules(){
         $rules = parent::rules();
         return array_merge([
                [['name','user_id' ,'type'], 'string'],
            ], $rules);
         }
    public function fields(){
        $fields = parent::fields();
        isset($this->user_id)&& $fields['user_id']='user_id';
        isset($this->type)&& $fields['type']='type';
        return $fields;
    }
}
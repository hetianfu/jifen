<?php
namespace api\modules\seller\models\forms;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-04-23
 */

class CouponUserQuery  extends BaseQuery{
     public  $title;
     public  $user_id;
     public $type;
    public $status;
     public function rules(){
         $rules = parent::rules();
         return array_merge([
                [['title','user_id' ,'type'], 'string'],
             [['status'], 'integer'],
            ], $rules);
         }
    public function fields(){
        $fields = parent::fields();
        isset($this->user_id)&&$fields['user_id']='user_id';
        isset($this->title)&&$fields['title']='title';
        isset($this->type)&&$fields['type']='type';
        isset($this->status)&&$fields['status']='status';
        return $fields;
    }
}
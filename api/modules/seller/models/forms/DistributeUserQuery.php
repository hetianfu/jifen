<?php
namespace api\modules\seller\models\forms;

/**
 * @author E-mail: yuannge@163.com
 * Create Time: 2020-09-01
 */

class DistributeUserQuery  extends BaseQuery{
     public  $name;
     public  $status;
    public  $identify;
     public  $parent_id;
    public  $telephone;
     public function rules(){
         $rules = parent::rules();
         return array_merge([
                [['name','parent_id','telephone' ], 'string'],
             [['status','identify' ], 'integer'],
            ], $rules);
         }
    public function fields(){
        $fields = parent::fields();
        isset($this->status)&&$fields['status']='status';
        isset($this->parent_id)&&$fields['parent_id']='parent_id';
        isset($this->identify)&&$fields['identify']='identify';
        isset($this->telephone)&&$fields['telephone']='telephone';

        return $fields;
    }
}
<?php
namespace api\modules\seller\models\forms;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-06-13
 */

class InformationCategoryQuery  extends BaseQuery{
     public  $title;
     public  $pid;
     public $status;
     public $is_system;
     public function rules(){
         $rules = parent::rules();
         return array_merge([
                [['title' ], 'string'],
                [['is_system','pid','status' ], 'integer'],
            ], $rules);
         }
    public function fields(){
        $fields = parent::fields();
        isset($this->title)&&$fields['title']='title';
        isset($this->is_system)&&$fields['is_system']='is_system';
        isset($this->pid)&&$fields['pid']='pid';
        isset($this->status)&&$fields['status']='status';
        return $fields;
    }
}
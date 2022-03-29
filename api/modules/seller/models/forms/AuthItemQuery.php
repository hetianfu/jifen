<?php
namespace api\modules\seller\models\forms;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-03-25
 */

class AuthItemQuery  extends BaseQuery{
   public $is_menu;
    public $pid;
    public $title;
    public $merchant_id;

    public function rules(){
     $rules = parent::rules();
     return array_merge([
          [['is_menu' ,'title','merchant_id'], 'string'],
          [['pid' ], 'integer'],
     ], $rules);
     }
    public function fields(){
        $fields = parent::fields();
        isset($this->is_menu)&&$fields['is_menu']='is_menu';
        isset($this->pid)&&$fields['pid']='pid';
        isset($this->title)&&$fields['title']='title';
        isset($this->merchant_id)&&$fields['merchant_id']='merchant_id';
        return $fields;
    }
}
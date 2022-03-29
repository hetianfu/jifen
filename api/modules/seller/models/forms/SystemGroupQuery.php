<?php
namespace api\modules\seller\models\forms;

/**
 * Class SystemGroupQuery
 * @package api\modules\seller\models\forms
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-06-02 14:42
 */

class SystemGroupQuery  extends BaseQuery{
     public  $name;
     public  $type;
     public function rules(){
         $rules = parent::rules();
         return array_merge([
                [['name','type' ], 'string'],
            ], $rules);
         }
    public function fields(){
        $fields = parent::fields();
        isset($this->type)&&$fields['type']='type';
        isset($this->name)&&$fields['name']='name';
        return $fields;
    }
}
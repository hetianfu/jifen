<?php
namespace api\modules\mobile\models\forms;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-04-28
 */

class SystemGroupQuery  extends BaseQuery{
     public  $name;
     public  $config_name;
     public function rules(){
         $rules = parent::rules();
         return array_merge([
                [['name','config_name' ], 'string'],
            ], $rules);
         }
    public function fields(){
        $fields = parent::fields();
        isset($this->configName)&&$fields['config_name']='config_name';
        isset($this->name)&&$fields['name']='name';
        return $fields;
    }
}
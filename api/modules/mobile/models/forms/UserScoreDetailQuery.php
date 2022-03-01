<?php
namespace api\modules\mobile\models\forms;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-04-23
 */

class UserScoreDetailQuery  extends BaseQuery{
     public  $name;
    public  $type;
     public function rules(){
         $rules = parent::rules();
         return array_merge([
                [['name','type'  ], 'string'],
            ], $rules);
         }
    public function fields(){
        $fields = parent::fields();
        isset($this->type)&&$fields['type']='type';
        return $fields;
    }
}
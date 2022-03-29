<?php
namespace api\modules\seller\models\forms;

/**
 * @author E-mail: yuannge@163.com
 * Create Time: 2020-11-16
 */

class ChannelQuery  extends BaseQuery{
     public  $name;
     public function rules(){
         $rules = parent::rules();
         return array_merge([
                [['name' ], 'string'],
            ], $rules);
         }
    public function fields(){
        $fields = parent::fields();
        return $fields;
    }
}
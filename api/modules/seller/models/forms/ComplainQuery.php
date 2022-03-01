<?php
namespace api\modules\seller\models\forms;

/**
 * @author E-mail: yuannge@163.com
 * Create Time: 2021-07-31
 */

class ComplainQuery  extends BaseQuery{
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
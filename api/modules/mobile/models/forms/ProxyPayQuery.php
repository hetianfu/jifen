<?php
namespace api\modules\mobile\models\forms;

/**
 * @author E-mail: yuannge@163.com
 * Create Time: 2020-10-09
 */

class ProxyPayQuery  extends BaseQuery{
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
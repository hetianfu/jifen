<?php
namespace api\modules\mobile\models\forms;

/**
 * Class UserCommissionQuery
 * @package api\modules\mobile\models\forms
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-06-04 17:13
 */

class UserCommissionQuery  extends BaseQuery{
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
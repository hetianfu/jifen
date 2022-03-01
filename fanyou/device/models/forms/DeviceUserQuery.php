<?php
namespace fanyou\device\models\forms;

use api\modules\seller\models\forms\BaseQuery;

/**
 * @author E-mail: yuannge@163.com
 * Create Time: 2020-09-02
 */

class DeviceUserQuery  extends BaseQuery{
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
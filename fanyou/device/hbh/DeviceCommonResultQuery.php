<?php
namespace fanyou\device\hbh;

/**
 * @author E-mail: yuannge@163.com
 * Create Time: 2020-09-02
 */

class DeviceCommonResultQuery  extends BaseQuery{
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
<?php
namespace api\modules\mobile\models\forms;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-04-23
 */

class CouponQuery  extends BaseQuery{
     public  $name;
     public  $status=1;
     public function rules(){
         $rules = parent::rules();
         return array_merge([
                [['name' ], 'string'],
                [['status' ], 'integer'],
            ], $rules);
         }
    public function fields(){
        $fields = parent::fields();
        isset($this->status)&&$fields['status']='status';
        return $fields;
    }
}
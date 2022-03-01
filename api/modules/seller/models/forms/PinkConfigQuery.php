<?php
namespace api\modules\seller\models\forms;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-08-20
 */

class PinkConfigQuery  extends BaseQuery{
     public  $product_id;
    public  $status;
     public function rules(){
         $rules = parent::rules();
         return array_merge([
                [['product_id','status' ], 'number'],
            ], $rules);
         }
    public function fields(){
        $fields = parent::fields();
        isset($this->status)&&$fields['status']='status';
        return $fields;
    }
}
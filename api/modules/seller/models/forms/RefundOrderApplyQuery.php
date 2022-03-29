<?php
namespace api\modules\seller\models\forms;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-05-21
 */

class RefundOrderApplyQuery  extends BaseQuery{
     public  $status;
     public  $type;
     public function rules(){
         $rules = parent::rules();
         return array_merge([
                [['status' ], 'integer'],
             [['type' ], 'string'],
            ], $rules);
         }
    public function fields(){
        $fields = parent::fields();
        isset($this->type)&&$fields['type']='type';
        isset($this->status)&&$fields['status']='status';
        return $fields;
    }
}
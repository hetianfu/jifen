<?php
namespace api\modules\mobile\models\forms;

/**
 * Class UserCommissionDetailQuery
 * @property int is_deduct
 * @package api\modules\mobile\models\forms
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-06-04 17:21
 */

class UserCommissionDetailQuery  extends BaseQuery{
     public  $remark;
     public  $type;
     public  $status;
     public  $is_deduct;
     public function rules(){
         $rules = parent::rules();
         return array_merge([
                [['remark','type' ], 'string'],
                 [[ 'status','is_deduct' ], 'integer'],
            ], $rules);
         }
    public function fields(){
        $fields = parent::fields();
        isset($this->is_deduct)&&$fields['is_deduct']='is_deduct';
        isset($this->type)&&$fields['type']='type';
        isset($this->status)&&$fields['status']='status';
        return $fields;
    }
}
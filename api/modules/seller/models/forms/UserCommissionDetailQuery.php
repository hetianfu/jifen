<?php
namespace api\modules\seller\models\forms;

/**
 * Class UserCommissionDetailQuery
 * @package api\modules\mobile\models\forms
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-06-04 17:21
 */

class UserCommissionDetailQuery  extends BaseQuery{
    public  $status;
    public  $type;
    public  $pay_type;
    public function rules(){
         $rules = parent::rules();
         return array_merge([
                 [['status'], 'integer'],
                [['type','pay_type'], 'string'],
            ], $rules);
         }
    public function fields(){
        $fields = parent::fields();
        isset($this->status)&&$fields['status']='status';

        isset($this->type)&&$fields['type']='type';
        isset($this->pay_type)&&$fields['pay_type']='pay_type';
        return $fields;
    }
}
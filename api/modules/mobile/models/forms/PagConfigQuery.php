<?php
namespace api\modules\mobile\models\forms;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-08-18
 */

class PagConfigQuery  extends BaseQuery{
     public  $identify;
    public  $status=1;
     public function rules(){
         $rules = parent::rules();
         return array_merge([
                [['identify' ], 'string'],
             [['status' ], 'integer'],
            ], $rules);
         }
    public function fields(){
        $fields = parent::fields();
        isset($this->identify)&&$fields['identify']='identify';
        isset($this->status)&&$fields['status']='status';
        return $fields;
    }
}
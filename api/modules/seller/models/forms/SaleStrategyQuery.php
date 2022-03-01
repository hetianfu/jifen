<?php
namespace api\modules\seller\models\forms;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-05-07
 */

class SaleStrategyQuery  extends BaseQuery{
     public  $name;
     public  $strategy_type;

     public function rules(){
         $rules = parent::rules();
         return array_merge([
                [['name','strategy_type' ], 'string'],
            ], $rules);
         }
    public function fields(){
        $fields = parent::fields();
        isset($this->strategy_type)&&$fields['strategy_type']='strategy_type';
        return $fields;
    }
}
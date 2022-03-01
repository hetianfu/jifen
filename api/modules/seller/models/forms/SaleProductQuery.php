<?php
namespace api\modules\seller\models\forms;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-05-07
 */

class SaleProductQuery  extends BaseQuery{
    public  $name;
    public  $strategy_type;
    public  $on_show;

     public function rules(){
         $rules = parent::rules();
         return array_merge([
                [['name','strategy_type' ], 'string'],
                [['on_show' ], 'integer'],
            ], $rules);
         }
    public function fields(){
        $fields = parent::fields();
        isset($this->name)&&$fields['name']='name';
        isset($this->strategy_type)&&$fields['strategy_type']='strategy_type';
        isset($this->on_show)&&$fields['on_show']='on_show';
        return $fields;
    }
}
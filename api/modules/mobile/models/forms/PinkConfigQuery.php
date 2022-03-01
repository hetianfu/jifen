<?php
namespace api\modules\mobile\models\forms;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-08-20
 */

class PinkConfigQuery  extends BaseQuery{
    public $productId;
    public $end_time;
    public $start_time;
    public $status = 1;
     public function rules(){
         $rules = parent::rules();
         return array_merge([
             [['productId'], 'string'],
             [['status','end_time'], 'integer']
            ], $rules);
         }
    public function fields(){
        $fields = parent::fields();
        isset($this->end_time) && $fields['end_time'] = 'end_time';
        isset($this->productId) && $fields['product_id'] = 'productId';
        isset($this->status) && $fields['status'] = 'status';
        unset($fields['productId']);
        return $fields;
    }
}
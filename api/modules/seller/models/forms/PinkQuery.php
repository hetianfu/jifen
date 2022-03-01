<?php

namespace api\modules\seller\models\forms;

/**
 * @property  product_id
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-08-20
 */
class PinkQuery extends BaseQuery
{
    public $product_name;
    public $product_id;
    public $end_time;
    public $status;
    public function rules()
    {
        $rules = parent::rules();
        return array_merge([
            [[ 'product_id','product_name'], 'string'],
            [['end_time','status'], 'integer'],

        ], $rules);
    }

    public function fields()
    {
        $fields = parent::fields();
        isset($this->product_id) && $fields['product_id'] = 'product_id';
        isset($this->end_time) && $fields['end_time'] = 'end_time';
        isset($this->status) && $fields['status'] = 'status';
        isset($this->product_name) && $fields['product_snap'] = 'product_name';
        return $fields;
    }
}
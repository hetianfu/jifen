<?php

namespace api\modules\mobile\models\forms;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-08-20
 */
class PinkQuery extends BaseQuery
{  public $user_id;
    public $product_id;
    public $status;
    public $is_effect = 1;
    public $end_time;

    public function rules()
    {
        $rules = parent::rules();
        return array_merge([
            [['name', 'product_id'], 'string'],
            [['end_time','is_effect','status'], 'integer'],

        ], $rules);
    }
    public function fields()
    {
        $fields = parent::fields();
        isset($this->user_id) && $fields['user_id'] = 'user_id';
        isset($this->product_id) && $fields['product_id'] = 'product_id';
        isset($this->status) && $fields['status'] = 'status';
        isset($this->is_effect) && $fields['is_effect'] = 'is_effect';

        isset($this->end_time) && $fields['end_time'] = 'end_time';
        return $fields;
    }
}
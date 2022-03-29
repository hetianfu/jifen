<?php

namespace api\modules\mobile\models\forms;

/**
 * @property int status
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-08-20
 */
class PinkPartakeQuery extends BaseQuery
{
    public $pink_id;
    public $status;
    public $orderId;

    public function rules()
    {
        $rules = parent::rules();
        return array_merge([
            [['pink_id','orderId'], 'string'],
            [['status'], 'integer'],
        ], $rules);
    }

    public function fields()
    {
        $fields = parent::fields();
        isset($this->pink_id) && $fields['pink_id'] = 'pink_id';
        isset($this->status) && $fields['status'] = 'status';
        isset($this->orderId) && $fields['order_id'] = 'orderId';
        unset($fields['orderId']);
        return $fields;
    }
}
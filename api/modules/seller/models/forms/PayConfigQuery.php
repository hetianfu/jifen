<?php
namespace api\modules\seller\models\forms;

/**
 * @property int id
         * @author E-mail: Administrator@qq.com
	 * Create Time: 2019/03/27
	 */
class PayConfigQuery extends BaseQuery
{
    public $name;
    public $status;

    public function rules()
    {
        $rules = parent::rules();
        return array_merge([
            [['name', ], 'string'],
           [['status'], 'integer'],
        ], $rules);
    }
    public function fields()
    {
        $fields  = parent::fields();
        $this->name &&$fields['name'] = 'name';
        isset($this->status) &&$fields['status'] = 'status';
        return $fields;
    }

}



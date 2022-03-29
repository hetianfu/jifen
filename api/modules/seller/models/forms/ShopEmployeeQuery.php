<?php

namespace api\modules\seller\models\forms;

/**
 * @property string shopId
 * @author E-mail: Administrator@qq.com
 * Create Time: 2019/03/27
 */
class ShopEmployeeQuery extends BaseQuery
{
    public $name;
    public $status;
    public $department_id;
    public function rules()
    {
        $rules = parent::rules();
        return array_merge([
            [[ 'name','department_id'], 'string'],
            [[ 'status' ], 'integer'],
        ], $rules);
    }

    public function fields()
    {
        $fields  =parent::fields();
        isset($this->department_id) &&$fields['department_ids'] = 'department_id';
        isset($this->status) &&$fields['status'] = 'status';
        isset($this->name) &&$fields['name'] = 'name';

        return $fields;
    }


}



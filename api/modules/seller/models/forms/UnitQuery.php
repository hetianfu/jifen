<?php
namespace api\modules\seller\models\forms;

/**
 * Class UnitQuery
 * @package api\modules\seller\models\forms
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2019-07-06 19:01
 */
class UnitQuery extends BaseQuery
{
    public $name;
    public function rules()
    {
        $rules = parent::rules();
        return array_merge([
            [['name' ], 'string'],
        ], $rules);
    }
    public function fields()
    {
        $fields = parent::fields();
        return $fields;
    }

}



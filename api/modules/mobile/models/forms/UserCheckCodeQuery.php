<?php
namespace api\modules\mobile\models\forms;

/**
 * @property int id
         * @author E-mail: Administrator@qq.com
	 * Create Time: 2019/03/27
	 */
class UserCheckCodeQuery extends BaseQuery
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



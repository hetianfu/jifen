<?php
namespace api\modules\manage\models\forms;

    /**
    * @property int id
     * @author E-mail: Administrator@qq.com
	 * Create Time: 2019/03/27
	 */
class UserCheckCodeQuery extends BaseQuery
{
    public $name;
    public $check_employee_id;
    public function rules()
    {
        $rules = parent::rules();
        return array_merge([
            [['name' ,'check_employee_id'], 'string'],
        ], $rules);
    }
    public function fields()
    {
        $fields = parent::fields();
        return $fields;
    }

}



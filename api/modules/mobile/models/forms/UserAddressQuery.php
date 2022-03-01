<?php
namespace api\modules\mobile\models\forms;

/**
 * @property int id
         * @author E-mail: Administrator@qq.com
	 * Create Time: 2019/03/27
	 */
class UserAddressQuery extends BaseQuery
{   public $user_id;
    public function rules()
    {
        $rules = parent::rules();
        return array_merge([
            [['user_id' ], 'string'],
        ], $rules);
    }
    public function fields()
    {
        $fields = parent::fields();
        isset($this->user_id)&&$fields['user_id']='user_id';

        return $fields;
    }

}



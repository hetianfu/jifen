<?php

namespace api\modules\mobile\models\forms;

/**
 * @property  userId
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-04-27
 */
class UserShopCartQuery extends BaseQuery
{
    public $name;
    public $user_id;

    public function rules()
    {
        $rules = parent::rules();
        return array_merge([
            [['name'], 'string'],
            [['user_id'], 'string'],
        ], $rules);
    }

    public function fields()
    {
        $fields  = parent::fields();
        isset($this->user_id )&&$fields['user_id'] = 'user_id';
        isset($this->name) &&$fields['name'] = 'name';
        return $fields;
    }


}
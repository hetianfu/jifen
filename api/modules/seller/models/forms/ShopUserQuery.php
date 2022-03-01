<?php
namespace api\modules\seller\models\forms;

/**
 * @property int id
         * @author E-mail: Administrator@qq.com
	 * Create Time: 2019/03/27
	 */
class ShopUserQuery extends BaseQuery
{
    public $user_code;
    public $user_name;
    public $telephone;
    public $status;

    public function rules()
    {
        $rules = parent::rules();
        return array_merge([
            [['user_code','user_name' ,'telephone'], 'string'],
            [['status',], 'integer'],
        ], $rules);
    }
    public function fields()
    {
        $fields  = parent::fields();
        isset($this->user_code) &&  $fields['user_code']='user_code';
        isset($this->user_name) &&  $fields['user_name']='user_name';
        isset($this->telephone) &&  $fields['telephone']='telephone';
        isset($this->status) &&  $fields['status']='status';
        return $fields;
    }

}



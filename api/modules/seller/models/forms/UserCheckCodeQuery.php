<?php
namespace api\modules\seller\models\forms;

/**
 * @property int id
         * @author E-mail: Administrator@qq.com
	 * Create Time: 2019/03/27
	 */
class UserCheckCodeQuery extends BaseQuery
{
    public $title;
    public $order_id;
    public $status;
    public $user_id;
    public function rules()
    {
        $rules = parent::rules();
        return array_merge([
            [['title','order_id' ,'user_id'], 'string'],
            [['status' ], 'integer'],

        ], $rules);
    }
    public function fields()
    {
        $fields = parent::fields();
        isset($this->title)&&$fields['title']='title';
        isset($this->order_id)&&$fields['order_id']='order_id';
        isset($this->user_id)&&$fields['user_id']='user_id';
        isset($this->status)&&$fields['status']='status';

        return $fields;
    }

}



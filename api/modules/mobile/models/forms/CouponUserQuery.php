<?php

namespace api\modules\mobile\models\forms;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-04-23
 */
class CouponUserQuery extends BaseQuery
{

    public $title;
    public $userId;
    public $status;
    public $isDel = 0;
    public $isOnce;

    public function rules()
    {
        $rules = parent::rules();
        return array_merge([
            [['title', 'userId', 'status'], 'string'],
            [['isDel'], 'integer'],
        ], $rules);
    }

    public function setAttributes($values, $safeOnly = true)
    {

        parent::setAttributes($values, $safeOnly);

    }

    public function fields()
    {
        $fields = parent::fields();
        isset($this->isDel) && $fields['is_del'] ='isDel';
        isset($this->userId) && $fields['user_id'] = 'userId';
        isset($this->isOnce) && $fields['is_once'] = 'isOnce';

        isset($this->status) && $fields['status'] = 'status';
        isset($this->title) && $fields['title'] = 'title';


        unset($fields['isDel'], $fields['userId'],$fields['isOnce']);
        return $fields;
    }


}
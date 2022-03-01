<?php
namespace api\modules\mobile\models\forms;

/**
 * @property int id
         * @author E-mail: Administrator@qq.com
	 * Create Time: 2019/03/27
	 */
class BasicOrderInfoQuery extends BaseQuery
{
    public $userId;
    public $status;
    public $addressSnap;
    public function rules()
    {
        $rules = parent::rules();
        return array_merge([
            [['userId', 'status','addressSnap'], 'string'],
        ], $rules);
    }


    public function setAttributes($values, $safeOnly = true) {

        parent::setAttributes($values, $safeOnly);

    }

    public function fields()
    {
        $fields = parent::fields();
        isset($this->userId) && $fields['user_id'] = 'userId';
        isset($this->addressSnap) && $fields['address_snap'] ='addressSnap';
        isset($this->status)&&$fields['status']='status';
        unset($fields['userId'],$fields['addressSnap']);

        return $fields;
    }

}



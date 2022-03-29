<?php

namespace api\modules\seller\models\forms;

/**
 * @author E-mail: yuannge@163.com
 * Create Time: 2020-11-16
 */
class SmsLogQuery extends BaseQuery
{
    public $telephone;
    public $sms_type;
    public $sms_code;
    public $status;
    public function rules()
    {
        $rules = parent::rules();
        return array_merge([
            [['telephone', 'sms_type', 'sms_code'], 'string'],
            [['status'], 'integer'],
        ], $rules);
    }

    public function fields()
    {
        $fields = parent::fields();
        isset($this->telephone)&&$fields['telephone']='telephone';
        isset($this->sms_type)&&$fields['sms_type']='sms_type';
        isset($this->sms_code)&&$fields['sms_code']='sms_code';
        isset($this->status)&&$fields['status']='status';
        return $fields;
    }
}
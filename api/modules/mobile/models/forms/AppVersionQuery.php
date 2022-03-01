<?php

namespace api\modules\mobile\models\forms;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-07-13
 */
class AppVersionQuery extends BaseQuery
{
    public $app_name;
    public $appid;
    public $status;

    public function rules()
    {
        $rules = parent::rules();
        return array_merge([
            [['app_name','appid'], 'string'],
            [['status'], 'integer'],
        ], $rules);
    }

    public function fields()
    {
        $fields = parent::fields();
        isset($this->appid) && $fields['appid'] = 'appid';
        isset($this->app_name) && $fields['app_name'] = 'app_name';
        isset($this->status) && $fields['status'] = 'status';
        return $fields;
    }
}
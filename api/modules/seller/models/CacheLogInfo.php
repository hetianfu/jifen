<?php

namespace api\modules\seller\models;

use yii\base\Model;
class CacheLogInfo extends Model
{

    public $name;
    public $account;

    public function fields()
    {
        $fields = parent::fields();

        return $fields;
    }

}

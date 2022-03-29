<?php

namespace api\modules\manage\models;

use yii\base\Model;

class CacheManage extends Model
{

    public $name;
    public $merchant_id;
    public $shop_id;
    public $id;
    public $sex;
    public $user_id;
    public $open_id;
    public function fields()
    {
        $fields = parent::fields();
        return $fields;
    }

}

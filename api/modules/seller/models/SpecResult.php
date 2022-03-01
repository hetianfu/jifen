<?php
namespace api\modules\seller\models;
use yii\base\Model;

class SpecResult  extends Model {

    public $name;
    public $showOrder;
    public $value;
    public function fields() {
        $fields = parent::fields();
        return $fields;
    }
}

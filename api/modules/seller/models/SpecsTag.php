<?php
namespace api\modules\seller\models;
use yii\base\Model;

class SpecsTag  extends Model {
    public $column;
    public $rows;
    public $detailColumn;
    public $detailRows;
    public function fields() {
        $fields = parent::fields();
        return $fields;
    }
}

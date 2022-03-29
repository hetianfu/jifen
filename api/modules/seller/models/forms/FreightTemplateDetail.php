<?php
namespace api\modules\seller\models\forms;
use yii\base\Model;

/**
 *
 */
class FreightTemplateDetail extends Model {

    public $id;
    public $first;
    public $firstAmount;
    public $other;
    public $otherAmount;

    public function fields(){
        $fields = parent::fields();
        return $fields;
    }


}


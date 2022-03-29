<?php
namespace api\modules\seller\models\event;
use yii\base\Event;


/**
 * @property  amount
 */
class OrderEvent extends Event {
    public $id;
    public  $number=1;
    public  $success=1;
    /**
     * 核销路径
     * @var
     */
    public  $checkMethod;
}


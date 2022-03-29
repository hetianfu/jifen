<?php
namespace api\modules\manage\models\event;
use yii\base\Event;


class OrderEvent extends Event {
    public $id;

    /**
     * 核销路径
     * @var
     */
    public  $checkMethod;
}


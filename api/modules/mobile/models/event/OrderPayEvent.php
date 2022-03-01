<?php
namespace api\modules\mobile\models\event;
use yii\base\Event;


/**
 * @property  attach
 */
class OrderPayEvent extends Event {
    public $orderInfo;
    public $cartList;
    public $openId;
    public $payAmount;
    public $attach;
}


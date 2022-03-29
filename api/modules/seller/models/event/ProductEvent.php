<?php
namespace api\modules\seller\models\event;
use yii\base\Event;


class ProductEvent extends Event {

    public $productId;
    public $categoryIdList;


}


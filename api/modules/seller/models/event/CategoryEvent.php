<?php
namespace api\modules\seller\models\event;
use yii\base\Event;


class CategoryEvent extends Event {
    public $categoryId;
    public $categoryName;
    public $updateName;

}


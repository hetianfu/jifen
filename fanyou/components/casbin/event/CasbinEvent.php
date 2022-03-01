<?php
namespace fanyou\components\casbin\event;
use yii\base\Event;

/**
 * Class CasbinEvent
 * @package api\modules\seller\models\event
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-07-14 9:24
 */
class CasbinEvent extends Event {
    public  $roleId;
    public  $items;

}


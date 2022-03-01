<?php

namespace fanyou\queue;

use Yii;

/**
 * Class Job
 * @package addons\RfExample\components
 */
class JobService
{

    /**
     * @param $job
     * @return string
     */
    public function sendMessage($job):string
    {
       return Yii::$app->queue->push($job);

    }
}
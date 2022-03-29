<?php

namespace common\queues;

use Yii;
use yii\base\BaseObject;

/**
 * Class LogJob
 * @package common\queues
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-07-10 10:46
 */
class LogJob extends BaseObject implements \yii\queue\JobInterface
{
    /**
     * 日志记录数据
     *
     * @var
     */
    public $data;

    /**
     * @param \yii\queue\Queue $queue
     * @return mixed|void
     * @throws \yii\base\InvalidConfigException
     */
    public function execute($queue)
    {
        Yii::$app->services->log->realCreate($this->data);
    }
}
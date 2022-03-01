<?php

namespace fanyou\queue;

use api\modules\mobile\models\forms\BasicOrderInfoModel;
use api\modules\mobile\service\BasicOrderInfoService;
use api\modules\seller\models\forms\ProductKillModel;
use yii\base\BaseObject;
use yii\queue\JobInterface;

/**
 * Class OrderStatisticJob
 * @package fanyou\queue
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-07-10 11:19
 */
class OrderStatisticJob extends BaseObject implements  JobInterface
{
    private $service;
    public function __construct()
    {
        $this->service = new BasicOrderInfoService();
    }

    /**
     * 内容
     *
     * @var
     */
    public $id;

    /**
     * 文件路径
     *
     * @var
     */
    public $status;

    /**
     * @param \yii\queue\Queue $queue
     * @return mixed|void
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function execute($queue)
    {
        $order=new BasicOrderInfoModel();
        $order->id=$this->id;
        $order->status=$this->status ;
        $this->service->updateBasicOrderInfoById($order);
        //$order->update();

    }
}
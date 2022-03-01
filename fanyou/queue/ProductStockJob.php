<?php

namespace fanyou\queue;

use api\modules\mobile\models\forms\ProductSkuModel;
use api\modules\mobile\service\ProductService;
use yii\base\BaseObject;
use yii\queue\JobInterface;

/**
 * Class ProductStockJob
 * @package fanyou\queue
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-07-10 11:19
 */
class ProductStockJob extends BaseObject implements  JobInterface
{
    private $service;
    public function __construct()
    {
        $this->service = new ProductService();
    }
    /**
     * 商品Id
     *
     * @var
     */
    public $id;

    /**
     * 文件路径
     *
     * @var
     */
    public $number;

    /**
     * @param \yii\queue\Queue $queue
     * @return mixed|void
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function execute($queue)
    {
        $model=new ProductSkuModel();
        $model->id=7;
       // $model->insert();
        $this->service->updateSkuById($model);
     ///   print_r("111111111111111");exit;
       // file_put_contents($this->file, $this->content);
    }
}
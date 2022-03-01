<?php

namespace fanyou\queue;

use api\modules\mobile\models\forms\UserWalletDetailModel;
use api\modules\mobile\service\DistributeConfigService;
use api\modules\mobile\service\UserInfoService;
use api\modules\mobile\service\UserWalletDetailService;
use fanyou\enums\entity\DistributeConfigEnum;
use fanyou\enums\StatusEnum;
use yii\base\BaseObject;
use yii\queue\JobInterface;

/**
 * Class UserDistributeJob
 * @package fanyou\queue
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-07-10 11:19
 */
class UserDistributeJob extends BaseObject implements JobInterface
{
    private $service;
    private $userService;
    private $walletDetailService;
    public function __construct()
    {
        $this->service = new DistributeConfigService();
        $this->userService = new UserInfoService();
        $this->walletDetailService=new UserWalletDetailService();
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
    public $amount;

    public $userId;

    /**
     * @param \yii\queue\Queue $queue
     * @return mixed|void
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function execute($queue)
    {
        $config = $this->service->getDistributeConfig();
        if (!empty($config[DistributeConfigEnum::DISTRIBUTE_STATUS])) {
            $isEvery=$config[DistributeConfigEnum::DISTRIBUTE_EVERY];
            $userInfo = $this->userService->getOneById($this->userId);
            //如果有上家，且有一级分销配置
            if (!empty($config[DistributeConfigEnum::DISTRIBUTE_FIRST]) && !empty($userInfo->parent_id)) {
                $parentId = $userInfo->parent_id;
                $parentInfo = $this->userService->getOneById($parentId);
                //如果没有上家
                if (empty($parentInfo)) {
                    return;
                }
                //如果是指定销售员分销，且上家不是销售员，则不增加帐单
                if (empty($isEvery) &&  empty($parentInfo->is_sales_person)) {
                    return;
                }
                //添加一级分销 帐单
                $this->addToWalletDetail($parentId,$this->amount*$config[DistributeConfigEnum::DISTRIBUTE_FIRST]);
                //如果有二级分销
                if (!empty($config[DistributeConfigEnum::DISTRIBUTE_SECOND])) {
                    $grandId=$parentInfo->parent_id;
                    $grandInfo = $this->userService->getOneById($grandId);
                    if (empty($grandInfo) || (empty($isEvery) && !empty($parentInfo->is_sales_person))) {
                        return;
                    }
                    //添加二级分销 帐单
                    $this->addToWalletDetail($grandId,$this->amount*$config[DistributeConfigEnum::DISTRIBUTE_SECOND]);
                }
            }

        }
    }
    private function addToWalletDetail($userId,$amount)
    {
        $model=new UserWalletDetailModel();
        $model->id=$this->id;
        $model->amount=$amount;
        $model->user_id=$userId;
        $model->sub_type=StatusEnum::COME_IN;
        $this->walletDetailService->addUserWalletDetail($model);
    }
}
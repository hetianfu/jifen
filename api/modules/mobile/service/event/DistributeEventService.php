<?php

namespace api\modules\mobile\service\event;

use api\modules\mobile\models\event\OrderPayEvent;
use api\modules\mobile\models\forms\ProductModel;
use api\modules\mobile\models\forms\UserCommissionDetailModel;
use api\modules\mobile\models\forms\UserInfoModel;
use api\modules\mobile\service\BasicService;
use api\modules\mobile\service\UserCommissionDetailService;
use api\modules\seller\models\forms\DistributeStatisticModel;
use fanyou\enums\entity\DistributeConfigEnum;
use fanyou\enums\PayStatusEnum;
use fanyou\enums\StatusEnum;
use fanyou\enums\SystemConfigEnum;
use fanyou\enums\WalletStatusEnum;
use fanyou\tools\ArrayHelper;
use fanyou\tools\SystemConfigHelper;
use yii\base\Exception;
use yii\db\Expression;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-04-23
 */
class DistributeEventService extends BasicService
{
    /*********************支付订单成功后，分销************************************/

    /**
     * 分销
     * @param OrderPayEvent $event
     * @throws \Throwable
     */
    public static function distribute(OrderPayEvent $event)
    {
        $userId = $event->orderInfo['user_id'];
        $orderId = $event->orderInfo['id'];

        $order = $event->orderInfo;
        $stockList = json_decode($order->cart_snap);
        $distribute = new SystemConfigHelper(SystemConfigEnum::DISTRIBUTE_CONFIG);
        $config = $distribute->getConfigValue();
        if (!empty($config[DistributeConfigEnum::DISTRIBUTE_STATUS])) {

            $userInfo = UserInfoModel::findOne($userId);

            $stockList = ArrayHelper::toArray($stockList);

            self::everyDistribute($config, $orderId, $userId, $userInfo->parent_id, $stockList,$order['pay_amount']);


        }
    }


    /**
     * 人人分销模式
     * @param $config
     * @param $orderId
     * @param $userId
     * @param $parentId
     * @param $stockList
     * @param $payAmount
     * @throws \Throwable
     */
    private static function everyDistribute($config, $orderId, $userId, $parentId, $stockList,$payAmount=0)
    {
        $hasGrand = false;
        $parentInfo = UserInfoModel::findOne(['id' => $parentId, 'status' => StatusEnum::ENABLED]);
        //如果没有上家
        if (empty($parentInfo) || $config[DistributeConfigEnum::FIRST_SHARED] != StatusEnum::ENABLED) {
            return;
        }
        $grandId = $parentInfo->parent_id;
        $grandInfo = UserInfoModel::findOne(['id' => $grandId, 'status' => StatusEnum::ENABLED]);
        //如果有二级分销
        if (!empty($grandInfo) && ($config[DistributeConfigEnum::SECOND_SHARED] == StatusEnum::ENABLED)) {
            $hasGrand = true;
        }

        $firstAmount = 0;
        $secondAmount = 0;
        $L0detail=[];
        $L1detail=[];

        foreach ($stockList as $k => $value) {
            $a = ArrayHelper::toArray($value);
            $shareConfig = self::getSharedAmount($a['id']);

            if (!empty($shareConfig)) {

                $parentAmount = isset($shareConfig[$parentInfo['identify']][0]) ? $shareConfig[$parentInfo['identify']][0] : 0;

                $thisL0Amount=$parentAmount * $a['number'];
                $detail0['name']=$value['name'];
                $detail0['amount']=$thisL0Amount;
                $L0detail[]=$detail0;
                $firstAmount += $thisL0Amount;
                if ($hasGrand) {
                    $grandAmount = isset($shareConfig[$grandInfo['identify']][1]) ? $shareConfig[$grandInfo['identify']][1] : 0;
                    $thisL1Amount=$grandAmount * $a['number'];
                    $detail1['name']=$value['name'];
                    $detail1['amount']=$thisL1Amount;
                    $L1detail[]=$detail1;
                    $secondAmount += $thisL1Amount;
                }
            }
        }
        if ($firstAmount <= 0) {
            return;
        }

        $service = new UserCommissionDetailService();
        //添加一级分销 帐单
        $parentDetail = new UserCommissionDetailModel();
        $parentDetail->is_deduct = StatusEnum::COME_IN;
        $parentDetail->amount = $firstAmount;
        $parentDetail->type = WalletStatusEnum::DISTRIBUTE;
        $parentDetail->user_id = $parentId;
        $parentDetail->open_id = $parentInfo->mini_app_open_id;
        $parentDetail->provider_id = $userId;
        $parentDetail->order_id = $orderId;
        $parentDetail->status = StatusEnum::SUCCESS;
        $parentDetail->pay_type = PayStatusEnum::WX;
        $parentDetail->level = $parentInfo['identify'];
        $parentDetail->detail =json_encode($L0detail,JSON_UNESCAPED_UNICODE);
        $service->addUserCommissionDetail($parentDetail);
        //统计业绩
        $thisMonth = date('Y-m', time());
        self::addToDistributeAmount($parentId,$thisMonth,$parentInfo['identify'],$payAmount);

        if (!empty($secondAmount) && $hasGrand) {
            //添加二级分销 帐单
            $grandDetail = new UserCommissionDetailModel();
            $grandDetail->is_deduct = StatusEnum::COME_IN;
            $grandDetail->amount = $secondAmount;
            $grandDetail->type = WalletStatusEnum::DISTRIBUTE;
            $grandDetail->user_id = $grandId;
            $grandDetail->open_id = $grandInfo->mini_app_open_id;
            $grandDetail->provider_id = $userId;
            $grandDetail->order_id = $orderId;
            $grandDetail->status = StatusEnum::SUCCESS;
            $grandDetail->pay_type = PayStatusEnum::WX;
            $grandDetail->level = $grandInfo['identify'];
            $grandDetail->detail =json_encode($L1detail,JSON_UNESCAPED_UNICODE);
            $service->addUserCommissionDetail($grandDetail);
            self::addToDistributeAmount($grandId,$thisMonth,$grandInfo['identify'],$payAmount);
        }

    }

    /**
     * 获取分销金额
     * @param $id
     * @return array|bool
     */
    private static function getSharedAmount($id)
    {
        $distributeConfig = ProductModel::find()->select(['distribute_config', 'is_distribute'])->where(['id' => $id])->one();
        $selfConfig = $distributeConfig['is_distribute'];
        if (!$selfConfig) {
            return null;
        } else {
            $distributeConfig = $distributeConfig['distribute_config'];
            return ArrayHelper::toArray(json_decode($distributeConfig));
        }
    }

    /**
     * 统计分销金额
     * @param $userId
     * @param $thisMonth
     * @param $level
     * @param $payAmount
     * @param bool $isParent
     * @throws \Throwable
     */
    private static function addToDistributeAmount($userId,$thisMonth,$level,$payAmount,$isParent=true)
    {
        try{
        $statistic = DistributeStatisticModel::findOne(['id' => $userId, 'this_month' => $thisMonth]);
        if (empty($statistic)) {
            $statistic = new  DistributeStatisticModel();
            $statistic->id = $userId;
            $statistic->this_month = $thisMonth;
            if ($isParent) {
            $statistic->disciple_amount = $payAmount;
            }else{
                $statistic->other_amount = $payAmount;
            }
            $statistic->total_amount = $payAmount;
            $statistic->level_id = $level;
            $statistic->insert();
        } else {
            if ($isParent) {
                DistributeStatisticModel::updateAll(['disciple_amount' => new Expression('`disciple_amount` + ' . $payAmount)
                        , 'total_amount' => new Expression('`total_amount` + ' . $payAmount)]
                    , ['id' => $userId, 'this_month' => $thisMonth]);
            } else {
                DistributeStatisticModel::updateAll(['other_amount' => new Expression('`other_amount` + ' . $payAmount)
                        , 'total_amount' => new Expression('`total_amount` + ' . $payAmount)]
                    , ['id' => $userId, 'this_month' => $thisMonth]);
            }
        }
        }catch (Exception $e){

        }
    }
}
/**********************End Of Coupon 服务层************************************/


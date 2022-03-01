<?php

namespace fanyou\queue;

use api\modules\mobile\models\forms\BasicOrderInfoModel;
use api\modules\seller\models\event\OrderEvent;
use api\modules\seller\models\forms\RefundOrderApplyModel;
use api\modules\seller\models\forms\UserCommissionDetailModel;
use api\modules\seller\models\forms\UserWalletDetailModel;
use api\modules\seller\service\event\RefundDistributeEventService;
use api\modules\seller\service\event\RefundScoreEventService;
use api\modules\seller\service\RefundApplyService;
use api\modules\seller\service\UserWalletDetailService;
use fanyou\enums\entity\OrderStatusEnum;
use fanyou\enums\HttpErrorEnum;
use fanyou\enums\NumberEnum;
use fanyou\enums\PayStatusEnum;
use fanyou\enums\StatusEnum;
use fanyou\enums\WalletStatusEnum;
use fanyou\error\errorCode\ErrorOrder;
use fanyou\error\FanYouHttpException;
use fanyou\service\SendWxMsgService;
use fanyou\tools\StringHelper;
use Yii;
use yii\base\BaseObject;
use yii\queue\JobInterface;

/**
 * Class RefundOrderJob
 * @package fanyou\queue
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-09-5 11:20
 */
class RefundOrderJob extends BaseObject implements JobInterface
{

    private $ids;
    private $service;
    private $walletDetailService;

    public function __construct($ids)
    {
        $this->service = new RefundApplyService();
        $this->walletDetailService = new UserWalletDetailService();
        try {
            $this->ids = $ids;
            $this->done($ids);
        } catch (\Exception $e) {
        }
    }

    /**
     * @param \yii\queue\Queue $ids
     * @return mixed|void
     * @throws FanYouHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function done($ids)
    {
        $orderList = BasicOrderInfoModel::find()->select(['id', 'pay_type', 'status', 'user_id', 'pay_amount'])->where(['status' => OrderStatusEnum::PINKING])
            ->andWhere(['in', 'id', $ids])->all();

        if (count($orderList)) {
            foreach ($orderList as $k => $orderInfo) {
                $orderId = $orderInfo['id'];
                $orderAmount = $orderInfo['pay_amount'];
                //添加一条审批列表
                $verifyModel = new RefundOrderApplyModel();
                $verifyModel->id = $orderId;
                $verifyModel->refund_id = StringHelper::uuid();
                $verifyModel->origin_status = $orderInfo['status'];
                $verifyModel->pay_type = $orderInfo['pay_type'];
                $verifyModel->user_id = $orderInfo['user_id'];
                $verifyModel->status = StatusEnum::APPROVE;
                $verifyModel->order_amount = $orderAmount;
                $verifyModel->refund_amount = $orderAmount;

                $verifyModel->remark = '拼团失败退单';
                $result = $verifyModel->insert();
                if ($result) {
                    $result = self::approveRefundById($verifyModel->refund_id, $orderInfo);

                    if ($result) {
                        $event = new OrderEvent();
                        $event->id = $orderId;
                        RefundDistributeEventService::distribute($event);
                        RefundScoreEventService::refundScoreDetail($event);

                    }
                }
            }
        }
    }

    public function execute($ids)
    {

    }

    public function approveRefundById($refundId, $orderInfo)
    {
        $refundAmount = $orderInfo['pay_amount'];
        switch ($orderInfo['pay_type']) {
            case PayStatusEnum::WX:
                $config['refund_desc'] = '拼团失败退单';
                $wxRefundResult = Yii::$app->wechat->payment->refund->byOutTradeNumber($orderInfo['id'],
                    $refundId, intval(strval($refundAmount * NumberEnum::HUNDRED)), intval(strval($refundAmount * NumberEnum::HUNDRED)), $config);
                if(!($wxRefundResult['return_code'] == 'SUCCESS' && $wxRefundResult['return_msg'] == 'OK'
                    && $wxRefundResult['result_code'] == 'SUCCESS')) {
                    //记录失败日志
                };
                break;
            case PayStatusEnum::WALLET:

                //退款至钱包
                $wDetail = new UserWalletDetailModel();
                $wDetail->id = $orderInfo['id'];
                $wDetail->is_deduct = StatusEnum::COME_IN;
                $wDetail->amount = $refundAmount;
                $wDetail->user_id = $orderInfo['user_id'];
                $wDetail->type = WalletStatusEnum::REFUND;
                $this->walletDetailService->addUserWalletDetail($wDetail);
        }
        return BasicOrderInfoModel::updateAll(['status' => OrderStatusEnum::REFUND], ['id' => $orderInfo['id']]);
    }


}
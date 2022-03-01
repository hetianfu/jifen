<?php

namespace api\modules\seller\service\event;

use api\modules\mobile\models\forms\SystemFinanceModel;
use api\modules\seller\models\event\OrderEvent;
use api\modules\seller\models\forms\UserCommissionDetailModel;
use api\modules\seller\models\forms\UserCommissionModel;
use api\modules\seller\service\BasicService;
use fanyou\enums\StatusEnum;
use fanyou\enums\WalletStatusEnum;
use yii\db\Expression;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-04-23
 */
class RefundDistributeEventService extends BasicService
{
    /*********************订单退款成功后，分销回滚************************************/


    /**
     * 分销
     * @param OrderEvent $event
     */
    public static function distribute(OrderEvent $event)
    {
        $orderId = $event->id;
        $list = UserCommissionDetailModel::findAll(['order_id' => $orderId]);
        //部分支付方式没有佣金
        if (count($list)) {
            foreach ($list as $item) {
                $model = new   UserCommissionDetailModel();
                $model->id = null;
                $model->user_id = $item->user_id;
                if($item->type==WalletStatusEnum::MP_DT){
                    $model->type = WalletStatusEnum::MP_REFUND;
                }else{
                    $model->type = WalletStatusEnum::REFUND;
                }

                $model->is_deduct = StatusEnum::COME_OUT;
                $model->open_id = $item->open_id;
                $model->amount = $item->amount * StatusEnum::COME_OUT;
                $model->pay_type = $item->pay_type;
                $model->provider_id = $item->provider_id;
                $model->order_id = $item->order_id;
                $model->status = $item->status;
                $model->level = $item->level;
                self::refundCommission($model);
            }
        }
    }

    private static function refundCommission(UserCommissionDetailModel $model)
    {
        $result = $model->insert();
        $amount = $model->amount;
        UserCommissionModel::updateAll(['amount' => new Expression('`amount` + ' . $amount),
                'debt_amount' => new Expression('`debt_amount` + ' . $amount),
                'debt_number' => new Expression('`debt_number` + ' . 1)]
            , ['id' => $model->user_id]);
        if (!$result) {
            \Yii::warning('refundCommission-error' . json_encode($model, JSON_UNESCAPED_UNICODE), 'seller');
        }
        $event = new SystemFinanceModel();
        $event->user_id = $model->user_id;
        $event->nick_name = $model->real_name;
        $event->type = $model->type;
        $event->amount = $model->amount;
        $event->content = WalletStatusEnum::getDescribe($model->type);//  '用户钱包变动';
        $event->insert();
        return $result;
    }

}
/**********************End Of Coupon 服务层************************************/


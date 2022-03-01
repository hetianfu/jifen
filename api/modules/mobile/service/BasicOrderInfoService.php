<?php

namespace api\modules\mobile\service;

use api\modules\mobile\models\forms\BasicOrderInfoModel;
use api\modules\mobile\models\forms\BasicOrderInfoQuery;
use api\modules\mobile\models\forms\ProxyPayModel;
use api\modules\mobile\models\forms\RefundOrderApplyModel;
use api\modules\mobile\models\forms\UserCheckCodeModel;
use fanyou\enums\entity\StrategyTypeEnum;
use fanyou\enums\SortEnum;
use fanyou\tools\DaysTimeHelper;
use fanyou\enums\NumberEnum;
use fanyou\tools\ArrayHelper;
use fanyou\models\base\SearchModel;
use fanyou\enums\entity\OrderStatusEnum;
use fanyou\enums\HttpErrorEnum;
use fanyou\enums\QueryEnum;
use fanyou\enums\StatusEnum;
use fanyou\error\errorCode\ErrorOrder;
use fanyou\error\FanYouHttpException;

/**
 * Class BasicOrderInfoService
 * @package api\modules\mobile\service
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-06-11 17:05
 */
class BasicOrderInfoService
{
    private $userCheckCodeService;
    private $refundApplyService;

    public function __construct()
    {
        $this->userCheckCodeService = new UserCheckCodeService();
        $this->refundApplyService = new RefundOrderApplyService();

    }
    /*********************BasicOrderInfo模块服务层************************************/
    /**
     * 添加一条订单
     * @param BasicOrderInfoModel $model
     * @return mixed
     * @throws \Throwable
     */
    public function addBasicOrderInfo(BasicOrderInfoModel $model)
    {
        $model->insert();
        return $model->getPrimaryKey();
    }

    /**
     * 分页获取列表
     * @param BasicOrderInfoQuery $query
     * @return array
     * @throws \yii\web\NotFoundHttpException
     */
    public function getBasicOrderInfoPage(BasicOrderInfoQuery $query): array
    {
        $searchModel = new SearchModel([
            'model' => BasicOrderInfoModel::class,
            'scenario' => 'default',
            'partialMatchAttributes' => ['name','address_snap'], // 模糊查询
            'defaultOrder' => [
                'created_at' => SORT_DESC
            ],
            'pageSize' => $query->limit,
        ]);

        $searchWord = $searchModel->search($query->toArray([], [QueryEnum::LIMIT]));
        $list = ArrayHelper::toArray($searchWord->getModels());

        foreach ($list as $k => $v) {
            $list[$k]['status'] = $this->checkCancelOrderById($v['id'], $v['status'], $v['unPaidSeconds']);
        }
        $result['list'] = $list;
        $result['totalCount'] = $searchWord->pagination->totalCount;
        return $result;
    }

    /**
     * 统计今日订单数
     * @return int
     */
    public function countToday(): int
    {
        return BasicOrderInfoModel::find()->where(['>', 'created_at', DaysTimeHelper::todayStart(true)])->count();
    }

    /**
     * 根据Id获取订单
     * @param $id
     * @return Object
     */
    public function getOneById($id)
    {
        $info = BasicOrderInfoModel::findOne($id);
           if (isset($info->un_paid_seconds)) {
        $info->status = $this->checkCancelOrderById($id, $info->status, $info->un_paid_seconds - time());
           }
        return $info;
    }

    /**
     * 获取用户最近一条订单
     * @param $userId
     * @return BasicOrderInfoModel
     */
    public function getLastOneByUserId($userId)
    {
        return BasicOrderInfoModel::find()
            ->where(['user_id' => $userId])
            ->andWhere(['is not', 'connect_snap', null])
            ->asArray()
            ->orderBy([SortEnum::CREATED_AT => SORT_DESC])
            ->one();

    }

    /**
     * 获取用户最近一条订单
     * @param $userId
     * @return BasicOrderInfoModel
     */
    public function getLastFreightOneByUserId($userId): ?BasicOrderInfoModel
    {
        $info = BasicOrderInfoModel::find()->where(['user_id' => $userId])
            ->andWhere(['is not', 'address_snap', null])->orderBy([SortEnum::CREATED_AT => SORT_DESC])->one();
        return $info;
    }

    /**
     * 获取订单剩余支付时间
     * @param $id
     * @return int
     */
    public function getOrderLeftPayTime($id): int
    {
        $info = $this->getOneById($id);
        if ($info->status === OrderStatusEnum::UNPAID) {
            $left = $info->un_paid_seconds - time();
            return empty($left) ? 0 : $left;
        }
        return 0;
    }

    /**
     * 根据Id更新订单
     * @param BasicOrderInfoModel $model
     * @return int
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function updateBasicOrderInfoById(BasicOrderInfoModel $model): int
    {
        $model->setOldAttribute('id', $model->id);
        return $model->update();
    }

    /**
     * 根据Id删除订单
     * @param $id
     * @return int
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function deleteById($id): int
    {
        $model = BasicOrderInfoModel::findOne($id);
        return $model->delete();
    }

    /**
     * 取消订单
     * @param $id
     * @return int
     */
    public function cancelOrderById($id): int
    {
        return BasicOrderInfoModel::updateAll(['status' => OrderStatusEnum::CANCELLED, 'refund_able' => StatusEnum::DISABLED], ['id' => $id]);

    }

    /**
     * 申请退款
     * @param RefundOrderApplyModel $apply
     * @return int
     * @throws FanYouHttpException
     * @throws \Throwable
     */
    public function refundOrderById(RefundOrderApplyModel $apply): int
    {
        $model = $this->getOneById($apply->id);
        if (empty($model) || $model->user_id != $apply->user_id) {

            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, ErrorOrder::NO_POWER_TO_REFUND);
        }
        if ($model->refund_able === StatusEnum::DISABLED
            || $model->status === OrderStatusEnum::CANCELLED || $model->status === OrderStatusEnum::REFUND
            || $model->status === OrderStatusEnum::REFUNDING || $model->status === OrderStatusEnum::UNPAID) {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, ErrorOrder::ORDER_CAN_NOT_REFUND);
        }
        $result = BasicOrderInfoModel::updateAll(['status' => OrderStatusEnum::REFUNDING, 'refund_able' => StatusEnum::DISABLED], ['id' => $model->id]);
        if ($result) {
            $apply->id = $apply->id;
            $proxyPay = ProxyPayModel::findOne(['prepay_id' => $model->prepay_id]);
            $apply->pay_order_id = $proxyPay->id;
            $apply->pay_type = $model->pay_type;
            $apply->status = StatusEnum::STATUS_INIT;
            $apply->order_amount = $model->pay_amount;
            $apply->refund_amount = $apply->order_amount;
            $apply->origin_status = $model->status;

            $this->refundApplyService->addRefundOrderApply($apply);
        }
        return $result;

    }

    /**
     * 检测订单是否需要取消
     * @param $id
     * @param $status
     * @param $unPaidSeconds
     * @return string
     */
    public function checkCancelOrderById($id, $status, $unPaidSeconds): ?string
    {
        if (($status === OrderStatusEnum::UNPAID) && ($unPaidSeconds <= 0)) {
            $status = OrderStatusEnum::CANCELLED;
        }
        return $status;
    }

    /**
     * 订单支付回调
     * 更改订单状态，生成提货码
     * @param BasicOrderInfoModel $order
     * @return int|mixed
     * @throws \Throwable
     */
    public function notifyOrderById(BasicOrderInfoModel $order)
    {
        $result = 0;
        $isRefund = StatusEnum::ENABLED;
        $stockList = ArrayHelper::toArray(json_decode($order->cart_snap));
        foreach ($stockList as $k => $value) {
            if (isset($value['isGoodRefund']) && ($value['isGoodRefund'] == StatusEnum::DISABLED)) {
                $isRefund = StatusEnum::DISABLED;
                break;
            }
        }
        if ($order->distribute) {
            //自提订单，状态改为待核销
            $orderStatus = OrderStatusEnum::UN_CHECK;
            $barCode = $this->getRandomCode();
            //如果是自提，生成用户核销码
            $model = new UserCheckCodeModel();
            $model->order_id = $order->id;
            $model->user_id = $order->user_id;
            $model->total_number = NumberEnum::ONE;
            $model->left_number = NumberEnum::ONE;
            $model->used_number = NumberEnum::ZERO;
            $model->bar_qrcode = $barCode;
            $model->check_shop_id = $order->cooperate_shop_id;
            $cartList = ArrayHelper::toArray(json_decode($order->cart_snap));
            $model->title = count($cartList) == 1 ? $cartList[0]['name'] : $cartList[0]['name'] . '等' . count($cartList) . '件商品';

            $barCode = $this->userCheckCodeService->addUserCheckCode($model);
            if ($barCode) {
                $result = BasicOrderInfoModel::updateAll(['status' => $orderStatus, 'refund_able' => $isRefund,
                    'show_bar_qrcode' => $barCode, 'paid_time' => time()], ['id' => $order->id]);
            }
        } else {
            //非自提订单，状态改为待发货
            $orderStatus = OrderStatusEnum::UN_SEND;
            $result = BasicOrderInfoModel::updateAll(['status' => $orderStatus, 'refund_able' => $isRefund,
                'paid_time' => time()], ['id' => $order->id]);
        }

        if ($order->order_product_type == StrategyTypeEnum::PINK) {
            BasicOrderInfoModel::updateAll(['status' => OrderStatusEnum::PINKING], ['id' => $order->id]);
        }
        return $result;
    }

    /**
     * 积分支付回调
     * 更改订单状态，生成提货码
     * @param BasicOrderInfoModel $order
     * @return int|mixed
     * @throws \Throwable
     */
    public function notifyScoreOrderById(BasicOrderInfoModel $order)
    {
        $result = 0;
        if ($order->distribute) {
            //自提订单，状态改为待核销
            $orderStatus = OrderStatusEnum::UN_CHECK;
            $barCode = $this->getRandomCode();
            //如果是自提，生成用户核销码
            $model = new UserCheckCodeModel();
            $model->order_id = $order->id;
            $model->user_id = $order->user_id;
            $model->total_number = NumberEnum::ONE;
            $model->left_number = NumberEnum::ONE;
            $model->used_number = NumberEnum::ZERO;
            $model->bar_qrcode = $barCode;
            $model->check_shop_id = $order->cooperate_shop_id;
            $cartList = ArrayHelper::toArray(json_decode($order->cart_snap));
            $model->title = count($cartList) == 1 ? $cartList[0]['name'] : $cartList[0]['name'] . '等' . count($cartList) . '件商品';
            $barCode = $this->userCheckCodeService->addUserCheckCode($model);
            if ($barCode) {
                $result = BasicOrderInfoModel::updateAll(['status' => $orderStatus,
                    'show_bar_qrcode' => $barCode, 'paid_time' => time()], ['id' => $order->id]);
            }
        } else {
            //非自提订单，状态改为待发货
            $orderStatus = OrderStatusEnum::UN_SEND;
            $result = BasicOrderInfoModel::updateAll(['status' => $orderStatus,
                'paid_time' => time()], ['id' => $order->id]);
        }
        return $result;
    }

    private function getRandomCode()
    {
        $code = mt_rand(NumberEnum::TEN_THOUSAND, NumberEnum::NINE_TEN_N_THOUSAND) . mt_rand(NumberEnum::TEN_THOUSAND, NumberEnum::NINE_TEN_N_THOUSAND)
            . mt_rand(NumberEnum::THOUSAND, NumberEnum::NINE_THOUSAND);
        while (!empty(UserCheckCodeModel::findOne(['bar_qrcode' => $code, 'status' => StatusEnum::DISABLED]))) {
            $code += 1;
        }
        return $code;
    }

}
/**********************End Of BasicOrderInfo 服务层************************************/


<?php

namespace api\modules\seller\service;

use AlipayTradeRefundContentBuilder;
use AlipayTradeService;
use api\modules\mobile\models\forms\ProxyPayModel;
use api\modules\seller\models\forms\BasicOrderInfoModel;
use api\modules\seller\models\forms\RefundOrderApplyModel;
use api\modules\seller\models\forms\RefundOrderApplyQuery;
use api\modules\seller\models\forms\UserCommissionDetailModel;
use api\modules\seller\models\forms\UserWalletDetailModel;
use api\modules\seller\service\wechat\WxPayService;
use EasyWeChat\Kernel\Support\Arr;
use fanyou\components\payment\alipay\AliPayCertLoad;
use fanyou\enums\entity\OrderStatusEnum;
use fanyou\enums\HttpErrorEnum;
use fanyou\enums\PayStatusEnum;
use fanyou\enums\QueryEnum;
use fanyou\enums\StatusEnum;
use fanyou\enums\WalletStatusEnum;
use fanyou\error\errorCode\ErrorOrder;
use fanyou\error\FanYouHttpException;
use fanyou\models\base\SearchModel;
use fanyou\tools\ArrayHelper;
use fanyou\tools\StringHelper;

/**
 * Class RefundApplyService
 * @package api\modules\seller\service
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-05-16 17:09
 */
class RefundApplyService
{
    private $wxPayService;
    private $walletDetailService;
    private $commissionDetailService;

    public function __construct()
    {
        $this->wxPayService = new WxPayService();
        $this->walletDetailService = new UserWalletDetailService();
        $this->commissionDetailService = new UserCommissionDetailService();

    }
    /*********************RefundOrderApply模块服务层************************************/
    /**
     * 分页获取列表
     * @param UserCommissionDetailQuery $query
     * @return array
     * @throws \yii\web\NotFoundHttpException
     */
    public function sum(RefundOrderApplyQuery $query)
    {
        $searchModel = new SearchModel([
            'model' => RefundOrderApplyModel::class,
            'scenario' => 'default',
            'partialMatchAttributes' => ['name'], // 模糊查询
            'defaultOrder' => $query->getOrdersArray(),
            'select' => ['refund_amount' => 'sum(refund_amount)', 'status'],
            'groupBy' => ['status']
        ]);
        $searchWord = $searchModel->search($query->toArray([], [QueryEnum::LIMIT]));

        return ArrayHelper::toArray($searchWord->getModels());
    }

    /**
     * 分页获取列表
     * @param RefundOrderApplyQuery $query
     * @return array
     * @throws \yii\web\NotFoundHttpException
     */
    public function getRefundOrderApplyPage(RefundOrderApplyQuery $query): array
    {
        $searchModel = new SearchModel([
            'model' => RefundOrderApplyModel::class,
            'scenario' => 'default',
            //'partialMatchAttributes' => ['name'], // 模糊查询
            'defaultOrder' => $query->getOrdersArray(),
            'pageSize' => $query->limit,
        ]);

        $searchWord = $searchModel->search($query->toArray([], [QueryEnum::LIMIT]));
        $result['list'] = ArrayHelper::toArray($searchWord->getModels());
        $result['totalCount'] = $searchWord->pagination->totalCount;
        return $result;
    }

    /**
     * 根据Id获取请退单列表
     * @param $id
     * @return RefundOrderApplyModel|null
     */
    public function getOneById($id): ?RefundOrderApplyModel
    {
        return RefundOrderApplyModel::findOne($id);
    }

    /**
     * 退单审核通过，并微信退款
     * @param RefundOrderApplyModel $model
     * @return int
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function approveRefundById(RefundOrderApplyModel $model): int
    {
        $refundAmount = $model->refund_amount;

        $old = $this->getOneById($model->id);
        if (empty($old) || !(in_array($old->origin_status, OrderStatusEnum::EFFECT_ARRAY))) {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, ErrorOrder::M_ORDER_CAN_NOT_REFUND);
        };
        if ($old->order_amount < $model->refund_amount) {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, ErrorOrder::M_ORDER_REFUND_OVER_LINE);
        };
        switch ($old->pay_type) {
            case PayStatusEnum::WX_MP:
            case PayStatusEnum::WX:
                $model->order_amount = $old->order_amount;
                $model->refund_id = $old->refund_id;
                $model->pay_order_id = $old->pay_order_id;
                $wxRefundResult=$this->wxPayService->refundPayOrder($model);

                if (!($wxRefundResult['return_code']=='SUCCESS' &&$wxRefundResult['return_msg']=='OK'
                    &&$wxRefundResult['result_code']=='SUCCESS')) {
                    throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, $wxRefundResult['return_msg'].$wxRefundResult['err_code_des']);
                };
                break;
            case PayStatusEnum::ALI_PAY:
                $model->order_amount = $old->order_amount;
                //商户订单号和支付宝交易号不能同时为空。 trade_no、  out_trade_no如果同时存在优先取trade_no
                //商户订单号，和支付宝交易号二选一
                $out_trade_no = $old->pay_order_id;
                //退款金额，不能大于订单总金额
                $refund_amount=$model->refund_amount;
                //退款的原因说明
                $refund_reason=$model->remark;

                //标识一次退款请求，同一笔交易多次退款需要保证唯一，如需部分退款，则此参数必传。
                $out_request_no=$old->refund_id;

                $RequestBuilder = new AlipayTradeRefundContentBuilder();
                $RequestBuilder->setOutTradeNo($out_trade_no);
                $RequestBuilder->setRefundAmount($refund_amount);
                $RequestBuilder->setRefundReason($refund_reason);
                $RequestBuilder->setOutRequestNo($out_request_no);

                $Response = new AlipayTradeService(AliPayCertLoad::getInitConfig('1'));
                $result=$Response->Refund($RequestBuilder);
                $array=ArrayHelper::toArray($result);
                $resultCode = $array['code'];
              //  throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, '操作失败'.json_encode($array,JSON_UNESCAPED_UNICODE));
                if(!empty($resultCode)&&$resultCode == 10000){
                } else {
                    throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, '操作失败'.$array['msg']);

                }

                break;
            case PayStatusEnum::WALLET:

                //退款至钱包
                $wDetail = new UserWalletDetailModel();
                $wDetail->id = $model->id;
                $wDetail->is_deduct = StatusEnum::COME_IN;
                $wDetail->amount = $refundAmount;
                $wDetail->user_id = $old->user_id;
                $wDetail->type = WalletStatusEnum::REFUND;
                $this->walletDetailService->addUserWalletDetail($wDetail);
        }

        $model->setOldAttribute('id', $model->id);
        $result = $model->update();
        if ($result) {
            //修改订单状态
            if ((!empty($model->origin_status)  ) && ($model->origin_status===$old->origin_status)) {
                BasicOrderInfoModel::updateAll(['status' => $old->origin_status,'refund_able' => StatusEnum::DISABLED], ['id' => $model->id]);
            } else {
                BasicOrderInfoModel::updateAll(['status' => OrderStatusEnum::REFUND,'refund_able' => StatusEnum::DISABLED], ['id' => $model->id]);
            }
        }
        return $result;
    }

    public function forbidRefundOrder(RefundOrderApplyModel $model): int
    {
        $result = $this->updateRefundOrderApplyById($model);
        if ($result) {
            $originStatus = $this->getOneById($model->id)->origin_status;
            BasicOrderInfoModel::updateAll(['status' => $originStatus], ['id' => $model->id]);
        }
        return $result;
    }

    /**
     * 根据Id更新请退单列表
     * @param RefundOrderApplyModel $model
     * @return int
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function updateRefundOrderApplyById(RefundOrderApplyModel $model): int
    {
        $model->setOldAttribute('id', $model->id);
        return $model->update();
    }

    /**
     * 根据Id删除请退单列表
     * @param $id
     * @return int
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function deleteById($id): int
    {
        $model = RefundOrderApplyModel::findOne($id);
        return $model->delete();
    }

    /**
     * 后台强制添加一条退款记录
     * @param RefundOrderApplyModel $apply
     * @return int
     * @throws FanYouHttpException
     * @throws \Throwable
     */
    public function forceToRefundOrderById(RefundOrderApplyModel $apply): int
    {
        $model = BasicOrderInfoModel::findOne($apply->id);
        if ($model->status === OrderStatusEnum::REFUNDING) {
            $result = 1;
        } else {
            if ($model->refund_able === StatusEnum::DISABLED
                || $model->status === OrderStatusEnum::CANCELLED || $model->status === OrderStatusEnum::REFUND
                || $model->status === OrderStatusEnum::UNPAID) {
                throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, ErrorOrder::ORDER_CAN_NOT_REFUND);
            }
            $result = BasicOrderInfoModel::updateAll(['status' => OrderStatusEnum::REFUNDING, 'refund_able' => StatusEnum::DISABLED], ['id' => $model->id]);
        }
        if ($result) {
            $d = RefundOrderApplyModel::findOne($apply->id);
            if (empty($d)) {
                $apply->id = $apply->id;

                $proxyPay=ProxyPayModel::findOne(['prepay_id'=>$model->prepay_id]);
                if(!empty($proxyPay)) {
                    $apply->pay_order_id = $proxyPay->id;
                } else{
                    //支付宝H5没有代付功能
                    $apply->pay_order_id = $apply->id;
                }
                $apply->pay_type = $model->pay_type;
                $apply->pay_type = $model->pay_type;
                $apply->user_id = $model->user_id;
                $apply->status = StatusEnum::STATUS_INIT;

                $apply->refund_id=$apply->refund_id;

                $apply->order_amount = $model->pay_amount;
                $apply->refund_amount = $apply->order_amount;
                $apply->origin_status = $model->status;
                $apply->remark = empty($apply->remark)?"后台操作退单！":$apply->remark;
                $apply->insert();
                }
        }
        return $result;

    }

}
/**********************End Of RefundOrderApply 服务层************************************/


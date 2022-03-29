<?php

namespace api\modules\seller\service;

use api\modules\mobile\service\WxPayService;
use api\modules\seller\models\forms\SystemFinanceModel;
use api\modules\seller\models\forms\UserCommissionDetailModel;
use api\modules\seller\models\forms\UserCommissionDetailQuery;
use api\modules\seller\models\forms\UserCommissionModel;
use api\modules\seller\models\forms\UserWalletDetailModel;

use fanyou\enums\HttpErrorEnum;
use fanyou\enums\NumberEnum;
use fanyou\enums\PayStatusEnum;
use fanyou\enums\QueryEnum;
use fanyou\enums\StatusEnum;
use fanyou\enums\WalletStatusEnum;
use fanyou\error\errorCode\ErrorUser;
use fanyou\error\FanYouHttpException;
use fanyou\models\base\SearchModel;
use fanyou\tools\ArrayHelper;
use yii\db\Expression;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-06-04
 */
class UserCommissionDetailService
{

    private $commissionService;
    private $wxPayService;
    private $walletDetailService;
    public function __construct()
    {
        $this->commissionService = new UserCommissionService();
        $this->wxPayService = new WxPayService();
        $this->walletDetailService=new UserWalletDetailService();
    }


    /*********************UserCommissionDetail模块服务层************************************/
    /**
     * 添加一条用户退款佣金详情
     * @param UserCommissionDetailModel $model
     * @return mixed
     * @throws \Throwable
     */
    public function addRefundDetail(UserCommissionDetailModel $model)
    {
        $model->amount=$model->amount*$model->is_deduct;
        if ($model->add()) {
            $commission = $this->commissionService->getOneById($model->user_id);
            switch ($model->type) {
                case WalletStatusEnum::REFUND:
                    if (empty($commission->id)) {
                        $commission = new UserCommissionModel();
                        $commission->id = $model->user_id;
                        $commission->status=StatusEnum::ENABLED;
                        $commission->amount = $model->amount;
                        $commission->total_amount = $commission->amount;
                        $commission->insert();
                    }
                    $this->refundAmount($model->user_id,$model->amount);
                    break;
            }
        }else{
            return 0;
        }
        return $model->getPrimaryKey();
    }


    /**
     * 同意提现
     * @param UserCommissionDetailModel $model
     * @return int|mixed
     * @throws FanYouHttpException
     * @throws \Throwable
     */
    public function approveDraw(UserCommissionDetailModel $model )
    {
        $userId=$model->user_id;
        $amount = $model->amount;
        $commission=$this->commissionService->getOneById($userId);
       if( ($commission->amount+$commission->debt_amount+$amount)<0){
           throw  new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, ErrorUser::USER_WALLET_LESS);
        }

        switch ($model->pay_type) {
            case PayStatusEnum::WX:
                $array = [
                    'partner_trade_no' => $model->id,
                    'openid' => $model->open_id,
                    'check_name' => 'NO_CHECK', // NO_CHECK：不校验真实姓名, FORCE_CHECK：强校验真实姓名
                    //  're_user_name' =>$apply['real_name'],// 如果 check_name 设置为FORCE_CHECK，则必填用户真实姓名
                    'amount' => intval(NumberEnum::N_HUNDRED * $amount), // 单位为分
                    'desc' => WalletStatusEnum::getDescribe(WalletStatusEnum::DRAW),
                ];
                $mchPay=$this->wxPayService->mchPayToBalance($array);

                if(  ($mchPay['return_code'] !='SUCCESS')  ||  ($mchPay['result_code'] !='SUCCESS')){
                    throw new FanYouHttpException(HttpErrorEnum::Locked, $mchPay['return_msg'].'---'.$mchPay['err_code_des'] );
                    return ;
                }

                $event=new SystemFinanceModel();
                $event->user_id=$userId;
                $event->nick_name= $model->real_name;
                $event->type=$model->type;
                $event->amount=$amount;
                $event->content=WalletStatusEnum::getDescribe(WalletStatusEnum::DRAW);
                if( !$event->insert()){
                    return ;
                }

                break;
            case PayStatusEnum::WALLET:
                //退回钱包
                $wDetail=new UserWalletDetailModel();
                $wDetail->user_id=$userId;
                $wDetail->amount=NumberEnum::N_ONE*$amount;
                $wDetail->is_deduct=StatusEnum::COME_IN;
                $wDetail->type=WalletStatusEnum::CHARGE;
                $wDetail->pay_type=PayStatusEnum::WALLET;
                $this->walletDetailService->addUserWalletDetail($wDetail);
                break;
        }

        $result=$this->drawAmount($userId, $amount);

        return $result;

    }
    /**
     * 分页获取列表
     * @param UserCommissionDetailQuery $query
     * @return array
     * @throws \yii\web\NotFoundHttpException
     */
    public function sum(UserCommissionDetailQuery $query)
    {
        $searchModel = new SearchModel([
            'model' => UserCommissionDetailModel::class,
            'scenario' => 'default',
            'partialMatchAttributes' => ['name'], // 模糊查询
            'defaultOrder' => $query->getOrdersArray(),
            'select'=>['amount'=>'sum(amount)','status'],
            'groupBy'=>['status']
        ]);
        $searchWord = $searchModel->search($query->toArray([], [QueryEnum::LIMIT]));

        return  ArrayHelper::toArray( $searchWord->getModels());
    }

    /**
     * 分页获取列表
     * @param UserCommissionDetailQuery $query
     * @return array
     * @throws \yii\web\NotFoundHttpException
     */
    public function getUserCommissionDetailPage(UserCommissionDetailQuery $query): array
    {
        $searchModel = new SearchModel([
            'model' => UserCommissionDetailModel::class,
            'scenario' => 'default',
            'partialMatchAttributes' => ['name'], // 模糊查询
            'defaultOrder' => $query->getOrdersArray(),
            'pageSize' => $query->limit,
        ]);
        $searchWord = $searchModel->search($query->toArray([], [QueryEnum::LIMIT]));
        $result['list'] = ArrayHelper::toArray($searchWord->getModels());
        $result['totalCount'] = $searchWord->pagination->totalCount;
        return $result;
    }

    /**
     * 根据Id获取金详情
     * @param $id
     * @return UserCommissionDetailModel|null
     */
    public function getOneById($id):?UserCommissionDetailModel
    {
        return UserCommissionDetailModel::findOne($id);
    }

    /**
     * 根据Id更新金详情
     * @param UserCommissionDetailModel $model
     * @return int
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function updateUserCommissionDetailById(UserCommissionDetailModel $model): int
    {
        $model->setOldAttribute('id', $model->id);
        return $model->update();
    }

    /**
     * 根据Id删除金详情
     * @param $id
     * @return int
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function deleteById($id): int
    {
        $model = UserCommissionDetailModel::findOne($id);
        return $model->delete();
    }

    /**
     * 提现
     * @param $userId
     * @param $amount
     * @return int
     */
    private function drawAmount($userId, $amount)
    {
      return  UserCommissionModel::updateAll(['amount' => new Expression('`amount` + ' . $amount), 'draw_amount' => new Expression('`draw_amount` - ' . $amount)]
            , ['id' => $userId]);


    }

    /**
     * 下家消费抽佣
     * @param $user_id
     * @param int $amount
     */
    private function consumeAmount($user_id, $amount)
    {
        UserCommissionModel::updateAll(['amount' => new Expression('`amount` + ' . $amount), 'total_amount' => new Expression('`total_amount` + ' . $amount)]
            , ['id' => $user_id]);
    }

    /**
     * 下家退单退佣金
     * @param string $user_id
     * @param   $amount
     */
    public function refundAmount($user_id, $amount)
    {
        UserCommissionModel::updateAll(['amount' => new Expression('`amount` + ' . $amount),
                'debt_amount' => new Expression('`debt_amount` + ' . $amount),
                'debt_number' => new Expression('`debt_number` + ' . 1)]
            , ['id' => $user_id]);
    }

    /**
     * 较验是否可以提现
     * @param UserCommissionDetailModel $model
     * @return UserCommissionDetailModel|null
     * @throws FanYouHttpException
     */
    public function verifyApplyDraw(UserCommissionDetailModel $model)
    {
        $old=$this->getOneById($model->id);

        if(empty($old) || $old->status!=StatusEnum::STATUS_INIT ){
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, ErrorUser::USER_REPEAT_DRAW);
        }

//        if ($old->user_id != $model->user_id  ) {
//            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, ErrorUser::USER_UN_LEGAL);
//        }
        $userComm = $this->commissionService->getOneById($old->user_id);

        if (empty($userComm) || empty($userComm->amount)) {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, ErrorUser::USER_WALLET_LESS);
        }
        if (($userComm->amount + $userComm->debt_amount) < $model->amount) {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, ErrorUser::USER_WALLET_LESS);
        }

        return $old;
    }
}
/**********************End Of UserCommissionDetail 服务层************************************/


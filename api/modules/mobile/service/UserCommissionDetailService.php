<?php

namespace api\modules\mobile\service;

use api\modules\mobile\models\forms\UserCommissionDetailModel;
use api\modules\mobile\models\forms\UserCommissionDetailQuery;
use api\modules\mobile\models\forms\UserCommissionModel;
use fanyou\enums\entity\DistributeConfigEnum;
use fanyou\enums\HttpErrorEnum;
use fanyou\enums\QueryEnum;
use fanyou\enums\SortEnum;
use fanyou\enums\StatusEnum;
use fanyou\enums\SystemConfigEnum;
use fanyou\enums\WalletStatusEnum;
use fanyou\error\errorCode\ErrorUser;
use fanyou\error\FanYouHttpException;
use fanyou\models\base\SearchModel;
use fanyou\tools\ArrayHelper;
use fanyou\tools\SystemConfigHelper;
use yii\db\Expression;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-06-04
 */
class UserCommissionDetailService
{
    private $commissionService;

    public function __construct()
    {
        $this->commissionService = new UserCommissionService();
    }

    /*********************UserCommissionDetail模块服务层************************************/
    /**
     * 添加一条用户佣金详情
     * @param UserCommissionDetailModel $model
     * @return mixed
     * @throws \Throwable
     */
    public function addUserCommissionDetail(UserCommissionDetailModel $model)
    {
        $model->amount=$model->amount*$model->is_deduct;
        if ($model->add()) {
            $commission = UserCommissionModel::findOne($model->user_id);
            switch ($model->type) {
                case WalletStatusEnum::CONSUME:
                case WalletStatusEnum::TEAM:
                case WalletStatusEnum::PROXY:
                case WalletStatusEnum::DISTRIBUTE:
                case WalletStatusEnum::MP_DT:
                    if (empty($commission)) {
                        $commission = new UserCommissionModel();
                        $commission->id = $model->user_id;
                        $commission->status=StatusEnum::ENABLED;
                        $commission->amount = $model->amount;
                        $commission->total_amount = $commission->amount;
                        $commission->insert();
                    }else{
                        if($model->is_deduct>0){
                            $this->consumeAmount($model->user_id,$model->amount);
                        }else if($model->is_deduct<0){
                            $this->refundAmount($model->user_id,$model->amount);
                        }
                    }
                    break;

//                case WalletStatusEnum::REFUND:
//                    $this->refundAmount($model->user_id,$model->amount);
//                    break;
//                case WalletStatusEnum::DRAW:
//                    if (empty($commission) || $model->is_deduct>0 ) {
//                        throw  new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,ErrorUser::USER_UN_LEGAL);
//                    }
//                    $this->drawAmount($model->user_id,$model->amount);
//
//                    break;
            }
        }else{
            return 0;
        }
        return $model->getPrimaryKey();
    }

    /**
     * 申请提现
     * @param UserCommissionDetailModel $model
     * @return mixed
     * @throws \Throwable
     */
    public function applyToDraw(UserCommissionDetailModel $model)
    {
        $model->amount=$model->amount*$model->is_deduct;
        if ($model->insert()) {
            return $model->getPrimaryKey();
        }
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
            'partialMatchAttributes' => ['remark'], // 模糊查询
            'defaultOrder' => $query->getOrdersArray(SortEnum::CREATED_AT, SORT_DESC),
            'pageSize' => $query->limit,
        ]);
        $searchWord = $searchModel->search($query->toArray([], [QueryEnum::LIMIT]));
        $result['list'] = ArrayHelper::toArray($searchWord->getModels());
        $result['totalCount'] = $searchWord->pagination->totalCount;
        return $result;
    }

    /**
     * 统计总金额
     * @param UserCommissionDetailQuery $query
     * @return array
     * @throws \yii\web\NotFoundHttpException
     */
    public function sumCommission(UserCommissionDetailQuery $query)
    {
        $column='amount';
        $searchModel = new SearchModel([
            'model' => UserCommissionDetailModel::class,
            'scenario' => 'default',
            'partialMatchAttributes' => ['remark'], // 模糊查询
           // 'defaultOrder' => $query->getOrdersArray(SortEnum::CREATED_AT, SORT_DESC),
            'select' => [$column=>'IFNULL(SUM(amount) ,0) '],
        ]);
        $searchWord = $searchModel->search($query->toArray());

        return  $searchWord->query->one()[$column];
    }
    /**
     * 根据Id获取金详情
     * @param $id
     * @return Object
     */
    public function getOneById($id)
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
     */
    private function drawAmount($userId,$amount)
    {
        UserCommissionModel::updateAll(['amount' => new Expression('`amount` + ' . $amount),'draw_amount' => new Expression('`draw_amount` + ' . $amount)]
            ,['id'=>$userId] );

    }

    /**
     * 下家消费抽佣
     * @param $user_id
     * @param int $amount
     */
    private function consumeAmount($user_id, $amount)
    {
        UserCommissionModel::updateAll(['amount' => new Expression('`amount` + ' . $amount),'total_amount' => new Expression('`total_amount` + ' . $amount)]
            ,['id'=>$user_id] );
    }

    /**
     * 下家退单退佣金
     * @param string $user_id
     * @param   $amount
     */
    private function refundAmount( $user_id, $amount)
    {
        UserCommissionModel::updateAll(['amount' => new Expression('`amount` + ' . $amount),
                'debt_amount' => new Expression('`debt_amount` + ' . $amount),
                 'debt_number' => new Expression('`debt_number` + ' . 1)  ]
            ,['id'=>$user_id] );
    }

    /**
     * 较验是否可以提现
     * @param UserCommissionDetailModel $model
     * @return UserCommissionDetailModel|null
     * @throws FanYouHttpException
     */
    public function verifyApplyDraw(UserCommissionDetailModel  $model):?UserCommissionDetailModel
    {
       if(!empty( UserCommissionDetailModel::findOne(['status'=>StatusEnum::STATUS_INIT,'user_id'=>$model->user_id]))){
           throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,ErrorUser::USER_REPEAT_DRAW);
       }

        $distribute=  new SystemConfigHelper(SystemConfigEnum::DISTRIBUTE_CONFIG) ;
        $info= $distribute->getConfigValue();
        if(isset($info[DistributeConfigEnum::DISTRIBUTE_MIN_DRAW])){
            $minDrawAmount=$info[DistributeConfigEnum::DISTRIBUTE_MIN_DRAW];
            if($model->amount<$minDrawAmount){
                throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,ErrorUser::MIN_DRAW_CASH.$minDrawAmount);
            }
        }
        $userComm=$this->commissionService->getOneById($model->user_id);
        if(empty($userComm) || empty($userComm->amount)){
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,ErrorUser::USER_WALLET_LESS);
        }
        if(($userComm->amount +$userComm->debt_amount) <$model->amount ){
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,ErrorUser::USER_WALLET_LESS);
        }

        return $model;
    }
}
/**********************End Of UserCommissionDetail 服务层************************************/


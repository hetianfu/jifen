<?php

namespace api\modules\mobile\service;

use api\modules\mobile\models\forms\UserInfoModel;
use api\modules\mobile\models\forms\UserWalletDetailModel;
use api\modules\mobile\models\forms\UserWalletDetailQuery;
use fanyou\enums\HttpErrorEnum;
use fanyou\enums\WalletStatusEnum;
use fanyou\error\errorCode\ErrorOrder;
use fanyou\error\errorCode\ErrorUser;
use fanyou\error\FanYouHttpException;
use fanyou\models\base\SearchModel;
use fanyou\tools\ArrayHelper;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-05-22
 */
class UserWalletDetailService
{

    private $userInfoService;

    public function __construct()
    {
        $this->userInfoService = new UserInfoService();
    }

/*********************UserWalletDetail模块服务层************************************/


    /**
	 * 添加一条用户钱包变动记录
	 * @param UserWalletDetailModel $model
     * @return mixed
     * @throws \Throwable
     */
	public function addUserWalletDetail(UserWalletDetailModel $model)
	{
        $userInfo=$this->userInfoService->getOneById($model->user_id);
        if(empty($userInfo) || $userInfo->status!=1){
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,ErrorUser::USER_UN_LEGAL);
        }
        if(! empty($model->id) ){
            if(!empty(UserWalletDetailModel::findOne(['id'=>$model->id]) )){
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,ErrorOrder::ORDER_HAD_PAID);
            }
        }

        $totalAmount=$userInfo->amount;
        $addAmount=$model->is_deduct*$model->amount;

        if(is_null($totalAmount)){
            $totalAmount=0;
        }

        if(($totalAmount + $addAmount )>=0){
            switch ($model->type){
                case WalletStatusEnum::CHARGE:

                     $this->userInfoService->chargeUserWallet($userInfo->id,$addAmount);break;
                case WalletStatusEnum::DRAW:
                    $this->userInfoService->drawUserWallet($userInfo->id,$addAmount);break;

                default:
                    $userUpdate=new UserInfoModel();
                    $userUpdate->id=$userInfo->id;
                    $userUpdate->amount=$totalAmount +$addAmount;
                    $this->userInfoService->updateUserInfoById($userUpdate);
                    break;
            }
        }else{
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,ErrorUser::USER_WALLET_LESS);
        }
        $model->amount=$addAmount;
        $model->balance=$totalAmount;
        $model->real_name=$userInfo->nick_name;
        $model->open_id=$userInfo->mini_app_open_id;
		$model->add();
		return $model->getPrimaryKey();
	}
	/**
	 * 分页获取列表
	 * @param UserWalletDetailQuery $query
	 * @return array
	 * @throws \yii\web\NotFoundHttpException
	 */
	public function getUserWalletDetailPage(UserWalletDetailQuery $query): array
	{
		$searchModel = new SearchModel([
			'model' => UserWalletDetailModel::class,
			'scenario' => 'default',
			'partialMatchAttributes' => ['name'], // 模糊查询
			'defaultOrder' => [
			'created_at' => SORT_DESC
			],
			'pageSize' => $query->limit,
		]);
		$searchWord = $searchModel->search(array_filter($query->toArray()));
		$result['list'] = ArrayHelper::toArray($searchWord->getModels());
		$result['totalCount'] = $searchWord->pagination->totalCount;
		return $result;
	}

	/**
	 * 根据Id获取包变动记录
	 * @param $id
	 * @return Object
	 */
	public function getOneById($id)
	{
		return UserWalletDetailModel::findOne($id);
	}

	/**
	 * 根据Id更新包变动记录
	 * @param UserWalletDetailModel $model
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function updateUserWalletDetailById (UserWalletDetailModel $model): int
	{
		$model->setOldAttribute('id',$model->id);
		return $model->update();
	}
	/**
	 * 根据Id删除包变动记录
	 * @param $id
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function deleteById ($id) : int
	{
		$model = UserWalletDetailModel::findOne($id);
		return  $model->delete();
	}

    /**
     * 较验是否可以提现
     * @param UserWalletDetailModel $model
     * @return bool
     */
//    public function verifyApplyDraw(UserWalletDetailModel $model):?UserWalletDetailModel
//    {   $result=false;
//        $redis=Yii::$app->user->identity;
//        $userInfo=$this->userInfoService->getOneById($redis['id']);
//        if(empty($userInfo) || empty($userInfo->status)){
//            return $result;
//        }
//        if(($userInfo->amount-$userInfo->debt_amount)>=$model->amount ){
//            $model->type=StatusEnum::COME_OUT;
//            $model->status=StatusEnum::STATUS_INIT;
//            $model->balance=$userInfo->amount;
//            $model->open_id= $userInfo->mini_app_open_id;
//            $model->extract_type=PayStatusEnum::WX;
//        }
//        return $model;
//    }
}
/**********************End Of UserWalletDetail 服务层************************************/


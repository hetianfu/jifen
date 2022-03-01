<?php

namespace api\modules\seller\service;

use api\modules\seller\models\forms\UserInfoModel;
use api\modules\seller\models\forms\UserWalletDetailModel;
use fanyou\enums\AppEnum;
use fanyou\enums\HttpErrorEnum;
use fanyou\enums\PayStatusEnum;
use fanyou\enums\StatusEnum;
use fanyou\enums\WalletStatusEnum;
use fanyou\error\errorCode\ErrorUser;
use fanyou\error\FanYouHttpException;
use Yii;

/**
 * Class UserWalletDetailService
 * @package api\modules\seller\service
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-06-05 15:49
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
        $totalAmount=$userInfo->amount;
        $addAmount=$model->is_deduct*$model->amount;

        if(is_null($totalAmount)){
            $totalAmount=0;
        }

        if(($totalAmount + $addAmount )>=0){
            switch ($model->type){
                case WalletStatusEnum::CHARGE:
                case WalletStatusEnum::DRAW:
                     $this->userInfoService->chargeUserWallet($userInfo->id,$addAmount);
                     break;
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
        $model->path= AppEnum::SELLER;
        $model->balance=$totalAmount;
        $model->real_name=$userInfo->nick_name;
        $model->open_id=$userInfo->mini_app_open_id;
        $model->amount= $addAmount;
		$model->add();
		return $model->getPrimaryKey();
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
    public function verifyApplyDraw(UserWalletDetailModel $model):?UserWalletDetailModel
    {   $result=false;
        $redis=Yii::$app->user->identity;
        $userInfo=$this->userInfoService->getOneById($redis['id']);
        if(empty($userInfo) || empty($userInfo->status)){
            return $result;
        }
        if(($userInfo->amount-$userInfo->debt_amount)>=$model->amount ){
            $model->type=StatusEnum::COME_OUT;
            $model->status=StatusEnum::STATUS_INIT;
            $model->balance=$userInfo->amount;
            $model->open_id= $userInfo->mini_app_open_id;
            $model->extract_type=PayStatusEnum::WX;
        }
        return $model;


    }
}
/**********************End Of UserWalletDetail 服务层************************************/


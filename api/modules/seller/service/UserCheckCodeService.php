<?php

namespace api\modules\seller\service;

use api\modules\seller\models\forms\BasicOrderInfoModel;
use api\modules\seller\models\forms\UserCheckCodeModel;
use api\modules\seller\models\forms\UserCheckCodeQuery;
use api\modules\seller\models\forms\UserCheckCodeRecordModel;
use fanyou\enums\entity\OrderStatusEnum;
use fanyou\enums\HttpErrorEnum;
use fanyou\enums\QueryEnum;
use fanyou\enums\StatusEnum;
use fanyou\error\errorCode\ErrorCheckCode;
use fanyou\error\FanYouHttpException;
use fanyou\tools\ArrayHelper;
use fanyou\models\base\SearchModel;
use yii\db\Expression;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-03-24
 */
class UserCheckCodeService
{

/*********************UserCoupon模块服务层************************************/
	/**
	 * 添加一条EX_用户卡券
	 * @param UserCheckCodeModel $model
     * @return mixed
     * @throws \Throwable
     */
	public function addUserCoupon(UserCheckCodeModel $model)
	{
		$model->insert();
		return $model->getPrimaryKey();
	}
	/**
	 * 分页获取列表
	 * @param UserCheckCodeQuery $query
	 * @return array
	 * @throws \yii\web\NotFoundHttpException
	 */
	public function getUserCouponPage(UserCheckCodeQuery $query): array
	{
		$searchModel = new SearchModel([
			'model' => UserCheckCodeModel::class,
			'scenario' => 'default',
			'partialMatchAttributes' => ['title','order_id','user_code'], // 模糊查询
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
     * 统计个数
     * @param UserScoreDetailQuery $query
     * @return mixed
     * @throws \yii\web\NotFoundHttpException
     */
    public function sum(UserCheckCodeQuery $query)
    {
        $searchModel = new SearchModel([
            'model' => UserCheckCodeModel::class,
            'scenario' => 'default',
            'partialMatchAttributes' => ['title'], // 模糊查询
            'defaultOrder' => [
                'created_at' => SORT_DESC
            ],
            'select'=>['total_number'=>'SUM(total_number)','used_number'=>'SUM(used_number)','left_number'=>'SUM(left_number)']
        ]);
        $searchWord = $searchModel->search($query->toArray());
        return  $searchWord->query->one();
    }
	/**
	 * 根据Id获取用户卡券
	 * @param $id
	 * @return Object
	 */
	public function getOneById($id)
	{
		return UserCheckCodeModel::findOne($id);
	}

	/**
	 * 根据Id更新用户卡券
	 * @param UserCheckCodeModel $model
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function updateUserCouponById (UserCheckCodeModel $model): int
	{
		$model->setOldAttribute('id',$model->id);
		return $model->update();
	}
	/**
	 * 根据Id删除用户卡券
	 * @param $id
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function deleteById ($id) : int
	{
		$model = UserCheckCodeModel::findOne($id);
		return  $model->delete();
	}


    /**
     * 分页获取列表
     * @param UserCheckCodeQuery $query
     * @return array
     * @throws \yii\web\NotFoundHttpException
     */
    public function getCheckOrderRecordPage(UserCheckCodeQuery $query): array
    {
        $searchModel = new SearchModel([
            'model' => UserCheckCodeRecordModel::class,
            'scenario' => 'default',
            'partialMatchAttributes' => ['title','user_code'], // 模糊查询
            'defaultOrder' => [
                'created_at' => SORT_DESC
            ],
            'pageSize' => $query->limit,
        ]);
        $searchWord = $searchModel->search( $query->toArray([],[QueryEnum::LIMIT]) );
        $result['list'] = ArrayHelper::toArray($searchWord->getModels());
        $result['totalCount'] = $searchWord->pagination->totalCount;
        return $result;
    }


    /**
     *  确认核销
     * @param $orderId
     * @param int $number
     * @return int
     * @throws FanYouHttpException
     * @throws \Throwable
     */
    public function verifyBarCode($orderId,$number=1  )
    {
        $array=\Yii::$app->user->identity;
        $info =  UserCheckCodeModel::findOne( ['order_id'=>$orderId] );

        if(!$array['isAdmin']){

            //如果是非管理员，则需要验证核销权限
            if ($info->check_shop_id != $array['shopId']) {
                throw  new FanYouHttpException(HttpErrorEnum::Expectation_Failed, ErrorCheckCode::NO_CHECK_POWER);
            }

            if (isset($info->expire_time) && $info->expire_time < time()) {
                throw  new FanYouHttpException(HttpErrorEnum::Expectation_Failed, ErrorCheckCode::CODE_HAD_EXPIRE_TIME);
            }
        }
        if ($info->status != StatusEnum::STATUS_INIT) {
            throw  new FanYouHttpException(HttpErrorEnum::Expectation_Failed, ErrorCheckCode::CODE_UN_EFFECT);
        }
        if ($info->left_number <= 0) {
            throw  new FanYouHttpException(HttpErrorEnum::Expectation_Failed, ErrorCheckCode::CODE_UN_LIMIT);
        }

        return $this->realVerify($info,  $array,$number);
    }

    /**
     * 核销逻辑
     * @param UserCheckCodeModel $info
     * @param  $array
     * @param int $number
     * @return int
     * @throws \Throwable
     */
    protected function realVerify(UserCheckCodeModel $info,  $array, $number = 1)
    {
        $orderInfo=$info->orderInfo;

        $model = new UserCheckCodeRecordModel();
        $model->check_source='SELLER';
        $model->title = $info->title;

        $model->product_snap = $orderInfo['cart_snap'];

        $model->order_id=$info->order_id;
        $model->number = $number;
        $model->type = $info->coupon_type;
        $model->bar_qrcode = $info->bar_qrcode;
        $model->check_shop_id = $info->check_shop_id;
        $model->user_id = $info->user_id;

        $model->merchant_id = $array['merchantId'];
        $model->check_employee_id = $array['account'];
        $model->check_employee_name = $array['name'];

        if ($model->insert()) {
            if ($info->left_number == $number) {
                return $this->verifyRecordStatus($info->id,$number,$info->bar_qrcode,$info->order_id);
            }
            return $this->verifyRecordStatus($info->id,$number, $info->bar_qrcode,null,false);
        }
    }

    /**
     * 更新核销后状态
     * @param $id
     * @param int $number
     * @param $barQrCode
     * @param bool $verifyAll
     * @param $orderId
     * @return int
     */
    protected function verifyRecordStatus($id,$number=1,$barQrCode,$orderId,$verifyAll = true)
    {
        if ($verifyAll) {
            BasicOrderInfoModel::updateAll(['status'=>OrderStatusEnum::CLOSED,
                'show_bar_qrcode'=>StatusEnum::USED.'_'.$barQrCode],['id'=>$orderId]);
            return UserCheckCodeModel::updateAll(['used_number' => new Expression('used_number+  ' . $number),
                    'left_number' => new Expression('left_number- ' . $number),
                    'bar_qrcode'=>StatusEnum::USED.'_'.$barQrCode,
                    'status' => StatusEnum::USED ]
                , ['and', ['id' => $id],['>=', 'left_number', $number]]
            );


        } else {
            return UserCheckCodeModel::updateAll(['used_number' => new Expression('used_number+  ' . $number),
                    'left_number' => new Expression('left_number- ' . $number)]
                , ['id' => $id ]);
        }
    }
}
/**********************End Of UserCoupon 服务层************************************/


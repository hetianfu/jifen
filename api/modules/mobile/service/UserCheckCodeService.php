<?php

namespace api\modules\mobile\service;

use api\modules\mobile\models\forms\UserCheckCodeModel;
use api\modules\mobile\models\forms\UserCheckCodeQuery;
use api\modules\mobile\models\forms\UserCheckCodeRecordModel;
use fanyou\models\base\SearchModel;
use fanyou\tools\ArrayHelper;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-03-24
 */
class UserCheckCodeService
{

/*********************UserCoupon模块服务层************************************/
	/**
	 * 添加一条EX_用户核销码
	 * @param UserCheckCodeModel $model
     * @return mixed
     * @throws \Throwable
     */
	public function addUserCheckCode(UserCheckCodeModel $model)
	{
	    $old=$this->getOneByOrderId($model->order_id);
	    if($old){
            return $old->bar_qrcode;
        }
		$model->insert();
		return $model->bar_qrcode;
	}
	/**
	 * 分页获取列表
	 * @param UserCheckCodeQuery $query
	 * @return array
	 * @throws \yii\web\NotFoundHttpException
	 */
	public function getUserCheckCodePage(UserCheckCodeQuery $query): array
	{
		$searchModel = new SearchModel([
			'model' => UserCheckCodeRecordModel::class,
			'scenario' => 'default',
			'partialMatchAttributes' => ['coupon_name','order_id','user_code'], // 模糊查询
			'defaultOrder' => [
			'created_at' => SORT_DESC
			],
			'pageSize' => $query->limit,
		]);
		$searchWord = $searchModel->search( $query->toArray([],['limit']) );
		$result['list'] = ArrayHelper::toArray($searchWord->getModels());
		$result['totalCount'] = $searchWord->pagination->totalCount;
		return $result;
	}

	/**
	 * 根据Id获取用户卡券
	 * @param $id
	 * @return Object
	 */
	public function getOneById($id):?UserCheckCodeModel
	{
		return UserCheckCodeModel::findOne($id);
	}
    /**
     * 根据订单Id获取用户卡券
     * @param $orderId
     * @return Object
     */
    public function getOneByOrderId($orderId):?UserCheckCodeModel
    {
        return UserCheckCodeModel::findOne(['order_id'=>$orderId]);
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
            'partialMatchAttributes' => ['coupon_name','user_code'], // 模糊查询
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


}
/**********************End Of UserCoupon 服务层************************************/


<?php

namespace api\modules\seller\service;

use api\modules\seller\models\forms\CouponUserModel;
use api\modules\seller\models\forms\CouponUserQuery;
use fanyou\enums\QueryEnum;
use fanyou\tools\ArrayHelper;
use fanyou\models\base\SearchModel;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-04-23
 */
class CouponUserService
{

/*********************CouponUser模块服务层************************************/
	/**
	 * 添加一条优惠券领取记录
	 * @param CouponUserModel $model
     * @return mixed
     * @throws \Throwable
     */
	public function addCouponUser(CouponUserModel $model)
	{   //获取卡券模版，获取卡券Id
//        print_r($model->toArray());exit;
//        $add=new CouponUserModel();
//        $add->coupon_id=$model->coupon_id;
//        $add->title=$model->title;
//        $add->type=$model->type;
//        $add->amount=$model->amount;
        $model->insert();
		return $model->getPrimaryKey();
	}
	/**
	 * 分页获取列表
	 * @param CouponUserQuery $query
	 * @return array
	 * @throws \yii\web\NotFoundHttpException
	 */
	public function getCouponUserPage(CouponUserQuery $query): array
	{
		$searchModel = new SearchModel([
			'model' => CouponUserModel::class,
			'scenario' => 'default',
			'partialMatchAttributes' => ['name'], // 模糊查询
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
	 * 根据Id获取领取记录
	 * @param $id
	 * @return Object
	 */
	public function getOneById($id)
	{
		return CouponUserModel::findOne($id);
	}

	/**
	 * 根据Id更新领取记录
	 * @param CouponUserModel $model
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function updateCouponUserById (CouponUserModel $model): int
	{
		$model->setOldAttribute('id',$model->id);
		return $model->update();
	}
	/**
	 * 根据Id删除领取记录
	 * @param $id
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function deleteById ($id) : int
	{
		$model = CouponUserModel::findOne($id);
		return  $model->delete();
	}
}
/**********************End Of CouponUser 服务层************************************/


<?php

namespace api\modules\seller\service;

use api\modules\seller\models\forms\UserAddressModel;
use api\modules\seller\models\forms\UserAddressQuery;
use fanyou\tools\ArrayHelper;
use fanyou\models\base\SearchModel;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-03-23
 */
class UserAddressService
{

/*********************UserAddress模块服务层************************************/
	/**
	 * 添加一条EX_收货地址
	 * @param UserAddressModel $model
     * @return mixed
     * @throws \Throwable
     */
	public function addUserAddress(UserAddressModel $model)
	{
		$model->insert();
		return $model->getPrimaryKey();
	}
	/**
	 * 分页获取列表
	 * @param UserAddressQuery $query
	 * @return array
	 * @throws \yii\web\NotFoundHttpException
	 */
	public function getUserAddressPage(UserAddressQuery $query): array
	{
		$searchModel = new SearchModel([
			'model' => UserAddressModel::class,
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
	 * 根据Id获取收货地址
	 * @param $id
	 * @return Object
	 */
	public function getOneById($id)
	{
		return UserAddressModel::findOne($id);
	}

	/**
	 * 根据Id更新收货地址
	 * @param UserAddressModel $model
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function updateUserAddressById (UserAddressModel $model): int
	{
		$model->setOldAttribute('id',$model->id);
		return $model->update();
	}
	/**
	 * 根据Id删除收货地址
	 * @param $id
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function deleteById ($id) : int
	{
		$model = UserAddressModel::findOne($id);
		return  $model->delete();
	}
}
/**********************End Of UserAddress 服务层************************************/


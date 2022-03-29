<?php

namespace api\modules\seller\service;

use api\modules\seller\models\forms\ShopUserModel;
use api\modules\seller\models\forms\ShopUserQuery;
use fanyou\tools\ArrayHelper;
use fanyou\models\base\SearchModel;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-03-23
 */
class ShopUserService
{

/*********************ShopUser模块服务层************************************/
	/**
	 * 添加一条店铺会员
	 * @param ShopUserModel $model
     * @return mixed
     * @throws \Throwable
     */
	public function addShopUser(ShopUserModel $model)
	{
		$model->insert();
		return $model->getPrimaryKey();
	}
	/**
	 * 分页获取列表
	 * @param ShopUserQuery $query
	 * @return array
	 * @throws \yii\web\NotFoundHttpException
	 */
	public function getShopUserPage(ShopUserQuery $query): array
	{
		$searchModel = new SearchModel([
			'model' => ShopUserModel::class,
			'scenario' => 'default',
			'partialMatchAttributes' => ['user_code','user_name','telephone'], // 模糊查询
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
	 * 根据Id获取员
	 * @param $id
	 * @return Object
	 */
	public function getOneById($id)
	{
		return ShopUserModel::findOne($id);
	}

	/**
	 * 根据Id更新员
	 * @param ShopUserModel $model
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function updateShopUserById (ShopUserModel $model): int
	{
		$model->setOldAttribute('id',$model->id);
		return $model->update();
	}
	/**
	 * 根据Id删除员
	 * @param $id
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function deleteById ($id) : int
	{
		$model = ShopUserModel::findOne($id);
		return  $model->delete();
	}
}
/**********************End Of ShopUser 服务层************************************/


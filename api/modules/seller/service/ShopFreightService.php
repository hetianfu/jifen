<?php

namespace api\modules\seller\service;

use api\modules\seller\models\forms\ShopFreightModel;
use api\modules\seller\models\forms\ShopFreightQuery;
use fanyou\tools\ArrayHelper;
use fanyou\models\base\SearchModel;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-05-14
 */
class ShopFreightService
{

/*********************ShopFreight模块服务层************************************/
	/**
	 * 添加一条店铺邮费
	 * @param ShopFreightModel $model
     * @return mixed
     * @throws \Throwable
     */
	public function addShopFreight(ShopFreightModel $model)
	{
		$model->insert();
		return $model->getPrimaryKey();
	}
	/**
	 * 分页获取列表
	 * @param ShopFreightQuery $query
	 * @return array
	 * @throws \yii\web\NotFoundHttpException
	 */
	public function getShopFreightPage(ShopFreightQuery $query): array
	{
		$searchModel = new SearchModel([
			'model' => ShopFreightModel::class,
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
	 * 根据Id获取费
	 * @param $id
	 * @return Object
	 */
	public function getOneById($id)
	{
		return ShopFreightModel::findOne($id);
	}

	/**
	 * 根据Id更新费
	 * @param ShopFreightModel $model
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function updateShopFreightById (ShopFreightModel $model): int
	{
		$model->setOldAttribute('id',$model->id);
		return $model->update();
	}
	/**
	 * 根据Id删除费
	 * @param $id
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function deleteById ($id) : int
	{
		$model = ShopFreightModel::findOne($id);
		return  $model->delete();
	}
}
/**********************End Of ShopFreight 服务层************************************/


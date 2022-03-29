<?php

namespace api\modules\seller\service;

use api\modules\seller\models\forms\ProductKillModel;
use api\modules\seller\models\forms\ProductKillQuery;
use fanyou\tools\ArrayHelper;
use fanyou\models\base\SearchModel;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-04-26
 */
class ProductKillService
{

/*********************ProductKill模块服务层************************************/
	/**
	 * 添加一条商品秒杀
	 * @param ProductKillModel $model
     * @return mixed
     * @throws \Throwable
     */
	public function addProductKill(ProductKillModel $model)
	{
		$model->insert();
		return $model->getPrimaryKey();
	}
	/**
	 * 分页获取列表
	 * @param ProductKillQuery $query
	 * @return array
	 * @throws \yii\web\NotFoundHttpException
	 */
	public function getProductKillPage(ProductKillQuery $query): array
	{
		$searchModel = new SearchModel([
			'model' => ProductKillModel::class,
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
	 * 根据Id获取杀
	 * @param $id
	 * @return Object
	 */
	public function getOneById($id)
	{
		return ProductKillModel::findOne($id);
	}

	/**
	 * 根据Id更新杀
	 * @param ProductKillModel $model
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function updateProductKillById (ProductKillModel $model): int
	{
		$model->setOldAttribute('id',$model->id);
		return $model->update();
	}
	/**
	 * 根据Id删除杀
	 * @param $id
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function deleteById ($id) : int
	{
		$model = ProductKillModel::findOne($id);
		return  $model->delete();
	}
}
/**********************End Of ProductKill 服务层************************************/


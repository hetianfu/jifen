<?php

namespace api\modules\seller\service;

use api\modules\seller\models\forms\ProductSkuResultModel;
use api\modules\seller\models\forms\ProductSkuResultQuery;
use fanyou\tools\ArrayHelper;
use fanyou\models\base\SearchModel;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-05-09
 */
class ProductSkuResultService
{

/*********************ProductSkuResult模块服务层************************************/
	/**
	 * 添加一条商品SKU展示
	 * @param ProductSkuResultModel $model
     * @return mixed
     * @throws \Throwable
     */
	public function addProductSkuResult(ProductSkuResultModel $model)
	{
		$model->insert();
		return $model->getPrimaryKey();
	}
	/**
	 * 分页获取列表
	 * @param ProductSkuResultQuery $query
	 * @return array
	 * @throws \yii\web\NotFoundHttpException
	 */
	public function getProductSkuResultPage(ProductSkuResultQuery $query): array
	{
		$searchModel = new SearchModel([
			'model' => ProductSkuResultModel::class,
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
	 * 根据Id获取KU展示
	 * @param $id
	 * @return Object
	 */
	public function getOneById($id)
	{
		return ProductSkuResultModel::findOne($id);
	}

	/**
	 * 根据Id更新KU展示
	 * @param ProductSkuResultModel $model
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function updateProductSkuResultById (ProductSkuResultModel $model): int
	{
		$model->setOldAttribute('id',$model->id);
		return $model->update();
	}
	/**
	 * 根据Id删除KU展示
	 * @param $id
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function deleteById ($id) : int
	{
		$model = ProductSkuResultModel::findOne($id);
		return  $model->delete();
	}
}
/**********************End Of ProductSkuResult 服务层************************************/


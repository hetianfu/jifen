<?php

namespace api\modules\seller\service;

use api\modules\seller\models\forms\SaleProductLimitModel;
use api\modules\seller\models\forms\SaleProductLimitQuery;
use fanyou\enums\QueryEnum;
use fanyou\tools\ArrayHelper;
use fanyou\models\base\SearchModel;

/**
 * Class SaleProductLimitService
 * @package api\modules\seller\service
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-08-26
 */
class SaleProductLimitService
{

/*********************SaleProductLimit模块服务层************************************/
	/**
	 * 添加一条商品限购
	 * @param SaleProductLimitModel $model
     * @return mixed
     * @throws \Throwable
     */
	public function addSaleProductLimit(SaleProductLimitModel $model)
	{
		$model->insert();
		return $model->getPrimaryKey();
	}
	/**
	 * 分页获取列表
	 * @param SaleProductLimitQuery $query
	 * @return array
	 * @throws \yii\web\NotFoundHttpException
	 */
	public function getSaleProductLimitPage(SaleProductLimitQuery $query): array
	{
		$searchModel = new SearchModel([
			'model' => SaleProductLimitModel::class,
			'scenario' => 'default',
			'partialMatchAttributes' => ['name'], // 模糊查询
			'defaultOrder' => $query->getOrdersArray(),
			'pageSize' => $query->limit,
		]);
		$searchWord = $searchModel->search( $query->toArray([],[QueryEnum::LIMIT]) );
		$result['list'] = ArrayHelper::toArray($searchWord->getModels());
		$result['totalCount'] = $searchWord->pagination->totalCount;
		return $result;
	}

	/**
	 * 根据Id获取商品限购
	 * @param $id
	 * @return Object
	 */
	public function getOneById($id)
	{
		return SaleProductLimitModel::findOne($id);
	}

	/**
	 * 根据Id更新商品限购
	 * @param SaleProductLimitModel $model
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function updateSaleProductLimitById (SaleProductLimitModel $model): int
	{
		$model->setOldAttribute('id',$model->id);
		return $model->update();
	}
	/**
	 * 根据Id删除商品限购
	 * @param $id
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function deleteById ($id) : int
	{
		$model = SaleProductLimitModel::findOne($id);
		return  $model->delete();
	}
}
/**********************End Of SaleProductLimit 服务层************************************/


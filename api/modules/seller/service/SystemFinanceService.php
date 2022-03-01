<?php

namespace api\modules\seller\service;

use api\modules\seller\models\forms\SystemFinanceModel;
use api\modules\seller\models\forms\SystemFinanceQuery;
use fanyou\enums\QueryEnum;
use fanyou\tools\ArrayHelper;
use fanyou\models\base\SearchModel;

/**
 * Class SystemFinanceService
 * @package api\modules\seller\service
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-06-16
 */
class SystemFinanceService
{

/*********************SystemFinance模块服务层************************************/
	/**
	 * 添加一条资金监控
	 * @param SystemFinanceModel $model
     * @return mixed
     * @throws \Throwable
     */
	public function addSystemFinance(SystemFinanceModel $model)
	{
		$model->insert();
		return $model->getPrimaryKey();
	}
	/**
	 * 分页获取资金流水
	 * @param SystemFinanceQuery $query
	 * @return array
	 * @throws \yii\web\NotFoundHttpException
	 */
	public function getSystemFinancePage(SystemFinanceQuery $query): array
	{
		$searchModel = new SearchModel([
			'model' => SystemFinanceModel::class,
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
	 * 根据Id获取控
	 * @param $id
	 * @return Object
	 */
	public function getOneById($id)
	{
		return SystemFinanceModel::findOne($id);
	}

	/**
	 * 根据Id更新控
	 * @param SystemFinanceModel $model
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function updateSystemFinanceById (SystemFinanceModel $model): int
	{
		$model->setOldAttribute('id',$model->id);
		return $model->update();
	}
	/**
	 * 根据Id删除控
	 * @param $id
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function deleteById ($id) : int
	{
		$model = SystemFinanceModel::findOne($id);
		return  $model->delete();
	}
}
/**********************End Of SystemFinance 服务层************************************/


<?php

namespace api\modules\seller\service;

use api\modules\seller\models\forms\ComplainModel;
use api\modules\seller\models\forms\ComplainQuery;
use fanyou\enums\QueryEnum;
use fanyou\tools\ArrayHelper;
use fanyou\models\base\SearchModel;

/**
 * Class ComplainService
 * @package api\modules\seller\service
 * @author: HenryJia
 * @E-mail: 316560925@qq.com
 * @date: 2021-07-31
 */
class ComplainService
{

/*********************Complain模块服务层************************************/
	/**
	 * 添加一条投诉
	 * @param ComplainModel $model
     * @return mixed
     * @throws \Throwable
     */
	public function addComplain(ComplainModel $model)
	{
		$model->insert();
		return $model->getPrimaryKey();
	}
	/**
	 * 分页获取列表
	 * @param ComplainQuery $query
	 * @return array
	 * @throws \yii\web\NotFoundHttpException
	 */
	public function getComplainPage(ComplainQuery $query): array
	{
		$searchModel = new SearchModel([
			'model' => ComplainModel::class,
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
	 * 根据Id获取投诉
	 * @param $id
	 * @return Object
	 */
	public function getOneById($id)
	{
		return ComplainModel::findOne($id);
	}

	/**
	 * 根据Id更新投诉
	 * @param ComplainModel $model
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function updateComplainById (ComplainModel $model): int
	{
		$model->setOldAttribute('id',$model->id);
		return $model->update();
	}
	/**
	 * 根据Id删除投诉
	 * @param $id
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function deleteById ($id) : int
	{
		$model = ComplainModel::findOne($id);
		return  $model->delete();
	}
}
/**********************End Of Complain 服务层************************************/


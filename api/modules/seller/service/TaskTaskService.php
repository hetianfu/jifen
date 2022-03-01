<?php

namespace api\modules\seller\service;

use api\modules\seller\models\forms\TaskTaskModel;
use api\modules\seller\models\forms\TaskTaskQuery;
use fanyou\enums\QueryEnum;
use fanyou\tools\ArrayHelper;
use fanyou\models\base\SearchModel;

/**
 * Class TaskTaskService
 * @package api\modules\seller\service
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-08-28
 */
class TaskTaskService
{

/*********************TaskTask模块服务层************************************/
	/**
	 * 添加一条
	 * @param TaskTaskModel $model
     * @return mixed
     * @throws \Throwable
     */
	public function addTaskTask(TaskTaskModel $model)
	{
		$model->insert();
		return $model->getPrimaryKey();
	}
	/**
	 * 分页获取列表
	 * @param TaskTaskQuery $query
	 * @return array
	 * @throws \yii\web\NotFoundHttpException
	 */
	public function getTaskTaskPage(TaskTaskQuery $query): array
	{
		$searchModel = new SearchModel([
			'model' => TaskTaskModel::class,
			'scenario' => 'default',
			'partialMatchAttributes' => ['name'], // 模糊查询
			'defaultOrder' => $query->getOrdersArray('id',SORT_ASC,'created',SORT_ASC),
			'pageSize' => $query->limit,
		]);
		$searchWord = $searchModel->search( $query->toArray([],[QueryEnum::LIMIT]) );
		$result['list'] = ArrayHelper::toArray($searchWord->getModels());
		$result['totalCount'] = $searchWord->pagination->totalCount;
		return $result;
	}

	/**
	 * 根据Id获取
	 * @param $id
	 * @return Object
	 */
	public function getOneById($id)
	{
		return TaskTaskModel::findOne($id);
	}

	/**
	 * 根据Id更新
	 * @param TaskTaskModel $model
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function updateTaskTaskById (TaskTaskModel $model): int
	{
		$model->setOldAttribute('id',$model->id);
		return $model->update();
	}
	/**
	 * 根据Id删除
	 * @param $id
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function deleteById ($id) : int
	{
		$model = TaskTaskModel::findOne($id);
		return  $model->delete();
	}
}
/**********************End Of TaskTask 服务层************************************/


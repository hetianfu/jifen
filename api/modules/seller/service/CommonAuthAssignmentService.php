<?php

namespace api\modules\seller\service;

use api\modules\seller\models\forms\CommonAuthAssignmentModel;
use api\modules\seller\models\forms\CommonAuthAssignmentQuery;
use fanyou\tools\ArrayHelper;
use fanyou\models\base\SearchModel;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-03-24
 */
class CommonAuthAssignmentService
{

/*********************CommonAuthAssignment模块服务层************************************/
	/**
	 * 添加一条公用_会员授权角色表
	 * @param CommonAuthAssignmentModel $model
     * @return mixed
     * @throws \Throwable
     */
	public function addCommonAuthAssignment(CommonAuthAssignmentModel $model)
	{
		$model->insert();
		return $model->getPrimaryKey();
	}
	/**
	 * 分页获取列表
	 * @param CommonAuthAssignmentQuery $query
	 * @return array
	 * @throws \yii\web\NotFoundHttpException
	 */
	public function getCommonAuthAssignmentPage(CommonAuthAssignmentQuery $query): array
	{
		$searchModel = new SearchModel([
			'model' => CommonAuthAssignmentModel::class,
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
	 * 根据Id获取会员授权角色表
	 * @param $id
	 * @return Object
	 */
	public function getOneById($id)
	{
		return CommonAuthAssignmentModel::findOne($id);
	}

	/**
	 * 根据Id更新会员授权角色表
	 * @param CommonAuthAssignmentModel $model
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function updateCommonAuthAssignmentById (CommonAuthAssignmentModel $model): int
	{
		$model->setOldAttribute('id',$model->id);
		return $model->update();
	}
	/**
	 * 根据Id删除会员授权角色表
	 * @param $id
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function deleteById ($id) : int
	{
		$model = CommonAuthAssignmentModel::findOne($id);
		return  $model->delete();
	}
}
/**********************End Of CommonAuthAssignment 服务层************************************/


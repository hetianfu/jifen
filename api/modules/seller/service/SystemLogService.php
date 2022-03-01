<?php

namespace api\modules\seller\service;

use api\modules\seller\models\forms\SystemLogModel;
use api\modules\seller\models\forms\SystemLogQuery;
use fanyou\enums\QueryEnum;
use fanyou\tools\ArrayHelper;
use fanyou\models\base\SearchModel;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-06-10
 */
class SystemLogService
{

/*********************SystemLog模块服务层************************************/
	/**
	 * 添加一条管理员操作记录表
	 * @param SystemLogModel $model
     * @return mixed
     * @throws \Throwable
     */
	public function addSystemLog(SystemLogModel $model)
	{
		$model->insert();
		return $model->getPrimaryKey();
	}
	/**
	 * 分页获取列表
	 * @param SystemLogQuery $query
	 * @return array
	 * @throws \yii\web\NotFoundHttpException
	 */
	public function getSystemLogPage(SystemLogQuery $query): array
	{
		$searchModel = new SearchModel([
			'model' => SystemLogModel::class,
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
	 * 根据Id获取操作记录表
	 * @param $id
	 * @return Object
	 */
	public function getOneById($id)
	{
		return SystemLogModel::findOne($id);
	}

	/**
	 * 根据Id更新操作记录表
	 * @param SystemLogModel $model
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function updateSystemLogById (SystemLogModel $model): int
	{
		$model->setOldAttribute('id',$model->id);
		return $model->update();
	}
	/**
	 * 根据Id删除操作记录表
	 * @param $id
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function deleteById ($id) : int
	{
		$model = SystemLogModel::findOne($id);
		return  $model->delete();
	}
}
/**********************End Of SystemLog 服务层************************************/


<?php

namespace api\modules\seller\service;

use api\modules\seller\models\forms\DistributeConfigModel;
use api\modules\seller\models\forms\DistributeConfigQuery;
use fanyou\tools\ArrayHelper;
use fanyou\models\base\SearchModel;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-05-22
 */
class DistributeConfigService
{

/*********************DistributeConfig模块服务层************************************/
	/**
	 * 添加一条用户分销配置
	 * @param DistributeConfigModel $model
     * @return mixed
     * @throws \Throwable
     */
	public function addDistributeConfig(DistributeConfigModel $model)
	{
		$model->insert();
		return $model->getPrimaryKey();
	}
	/**
	 * 分页获取列表
	 * @param DistributeConfigQuery $query
	 * @return array
	 * @throws \yii\web\NotFoundHttpException
	 */
	public function getDistributeConfigPage(DistributeConfigQuery $query): array
	{
		$searchModel = new SearchModel([
			'model' => DistributeConfigModel::class,
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
	 * 根据Id获取销配置
	 * @param $id
	 * @return Object
	 */
	public function getOneById($id)
	{
		return DistributeConfigModel::findOne($id);
	}

	/**
	 * 根据Id更新销配置
	 * @param DistributeConfigModel $model
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function updateDistributeConfigById (DistributeConfigModel $model): int
	{
		$model->setOldAttribute('id',$model->id);
		return $model->update();
	}
	/**
	 * 根据Id删除销配置
	 * @param $id
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function deleteById ($id) : int
	{
		$model = DistributeConfigModel::findOne($id);
		return  $model->delete();
	}
}
/**********************End Of DistributeConfig 服务层************************************/


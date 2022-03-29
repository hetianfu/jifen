<?php

namespace api\modules\seller\service;

use api\modules\seller\models\forms\UserScoreConfigModel;
use api\modules\seller\models\forms\UserScoreConfigQuery;
use fanyou\tools\ArrayHelper;
use fanyou\models\base\SearchModel;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-05-11
 */
class UserScoreConfigService
{

/*********************UserScoreConfig模块服务层************************************/
	/**
	 * 添加一条积分配置
	 * @param UserScoreConfigModel $model
     * @return mixed
     * @throws \Throwable
     */
	public function addUserScoreConfig(UserScoreConfigModel $model)
	{
		$model->insert();
		return $model->getPrimaryKey();
	}
	/**
	 * 分页获取列表
	 * @param UserScoreConfigQuery $query
	 * @return array
	 * @throws \yii\web\NotFoundHttpException
	 */
	public function getUserScoreConfigPage(UserScoreConfigQuery $query): array
	{
		$searchModel = new SearchModel([
			'model' => UserScoreConfigModel::class,
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
	 * 根据Id获取置
	 * @param $id
	 * @return Object
	 */
	public function getOneById($id)
	{
		return UserScoreConfigModel::findOne($id);
	}

	/**
	 * 根据Id更新置
	 * @param UserScoreConfigModel $model
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function updateUserScoreConfigById (UserScoreConfigModel $model): int
	{
		$model->setOldAttribute('id',$model->id);
		return $model->update();
	}
	/**
	 * 根据Id删除置
	 * @param $id
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function deleteById ($id) : int
	{
		$model = UserScoreConfigModel::findOne($id);
		return  $model->delete();
	}
}
/**********************End Of UserScoreConfig 服务层************************************/


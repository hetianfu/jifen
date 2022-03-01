<?php

namespace api\modules\seller\service;

use api\modules\seller\models\forms\UserLevelModel;
use api\modules\seller\models\forms\UserLevelQuery;
use fanyou\tools\ArrayHelper;
use fanyou\models\base\SearchModel;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-04-27
 */
class UserLevelService
{

/*********************UserLevel模块服务层************************************/
	/**
	 * 添加一条用户等级
	 * @param UserLevelModel $model
     * @return mixed
     * @throws \Throwable
     */
	public function addUserLevel(UserLevelModel $model)
	{
		$model->insert();
		return $model->getPrimaryKey();
	}
	/**
	 * 分页获取列表
	 * @param UserLevelQuery $query
	 * @return array
	 * @throws \yii\web\NotFoundHttpException
	 */
	public function getUserLevelPage(UserLevelQuery $query): array
	{
		$searchModel = new SearchModel([
			'model' => UserLevelModel::class,
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
	 * 根据Id获取级
	 * @param $id
	 * @return Object
	 */
	public function getOneById($id)
	{
		return UserLevelModel::findOne($id);
	}

	/**
	 * 根据Id更新级
	 * @param UserLevelModel $model
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function updateUserLevelById (UserLevelModel $model): int
	{
		$model->setOldAttribute('id',$model->id);
		return $model->update();
	}
	/**
	 * 根据Id删除级
	 * @param $id
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function deleteById ($id) : int
	{
		$model = UserLevelModel::findOne($id);
		return  $model->delete();
	}
}
/**********************End Of UserLevel 服务层************************************/


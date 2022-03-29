<?php

namespace api\modules\mobile\service;

use api\modules\mobile\models\forms\UserCodeModel;
use api\modules\mobile\models\forms\UserCodeQuery;
use fanyou\tools\ArrayHelper;
use fanyou\models\base\SearchModel;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-05-18
 */
class UserCodeService
{

/*********************UserCode模块服务层************************************/
	/**
	 * 添加一条用户编号
	 * @param UserCodeModel $model
     * @return mixed
     * @throws \Throwable
     */
	public function addUserCode(UserCodeModel $model)
	{
		$model->insert();
		return $model->getPrimaryKey();
	}
	/**
	 * 分页获取列表
	 * @param UserCodeQuery $query
	 * @return array
	 * @throws \yii\web\NotFoundHttpException
	 */
	public function getUserCodePage(UserCodeQuery $query): array
	{
		$searchModel = new SearchModel([
			'model' => UserCodeModel::class,
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
	 * 根据Id获取号
	 * @param $id
	 * @return Object
	 */
	public function getOneById($id)
	{
		return UserCodeModel::findOne($id);
	}

	/**
	 * 根据Id更新号
	 * @param UserCodeModel $model
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function updateUserCodeById (UserCodeModel $model): int
	{
		$model->setOldAttribute('id',$model->id);
		return $model->update();
	}
	/**
	 * 根据Id删除号
	 * @param $id
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function deleteById ($id) : int
	{
		$model = UserCodeModel::findOne($id);
		return  $model->delete();
	}
}
/**********************End Of UserCode 服务层************************************/


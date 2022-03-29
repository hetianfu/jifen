<?php

namespace api\modules\seller\service;

use api\modules\seller\models\forms\UserInfoGroupModel;
use api\modules\seller\models\forms\UserInfoGroupQuery;
use fanyou\tools\ArrayHelper;
use fanyou\models\base\SearchModel;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-04-27
 */
class UserInfoGroupService
{

/*********************UserInfoGroup模块服务层************************************/
	/**
	 * 添加一条会员组
	 * @param UserInfoGroupModel $model
     * @return mixed
     * @throws \Throwable
     */
	public function addUserInfoGroup(UserInfoGroupModel $model)
	{
		$model->insert();
		return $model->getPrimaryKey();
	}
	/**
	 * 分页获取列表
	 * @param UserInfoGroupQuery $query
	 * @return array
	 * @throws \yii\web\NotFoundHttpException
	 */
	public function getUserInfoGroupPage(UserInfoGroupQuery $query): array
	{
		$searchModel = new SearchModel([
			'model' => UserInfoGroupModel::class,
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
	 * 根据Id获取
	 * @param $id
	 * @return Object
	 */
	public function getOneById($id)
	{
		return UserInfoGroupModel::findOne($id);
	}

	/**
	 * 根据Id更新
	 * @param UserInfoGroupModel $model
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function updateUserInfoGroupById (UserInfoGroupModel $model): int
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
		$model = UserInfoGroupModel::findOne($id);
		return  $model->delete();
	}
}
/**********************End Of UserInfoGroup 服务层************************************/


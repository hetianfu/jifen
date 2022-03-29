<?php

namespace api\modules\seller\service;

use api\modules\seller\models\forms\WechatUserModel;
use api\modules\seller\models\forms\WechatUserQuery;
use fanyou\tools\ArrayHelper;
use fanyou\models\base\SearchModel;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-05-23
 */
class WechatUserService
{

/*********************WechatUser模块服务层************************************/
	/**
	 * 添加一条微信用户表
	 * @param WechatUserModel $model
     * @return mixed
     * @throws \Throwable
     */
	public function addWechatUser(WechatUserModel $model)
	{
		$model->insert();
		return $model->getPrimaryKey();
	}
	/**
	 * 分页获取列表
	 * @param WechatUserQuery $query
	 * @return array
	 * @throws \yii\web\NotFoundHttpException
	 */
	public function getWechatUserPage(WechatUserQuery $query): array
	{
		$searchModel = new SearchModel([
			'model' => WechatUserModel::class,
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
	 * 根据Id获取户表
	 * @param $id
	 * @return Object
	 */
	public function getOneById($id)
	{
		return WechatUserModel::findOne($id);
	}

	/**
	 * 根据Id更新户表
	 * @param WechatUserModel $model
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function updateWechatUserById (WechatUserModel $model): int
	{
		$model->setOldAttribute('id',$model->id);
		return $model->update();
	}
	/**
	 * 根据Id删除户表
	 * @param $id
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function deleteById ($id) : int
	{
		$model = WechatUserModel::findOne($id);
		return  $model->delete();
	}
}
/**********************End Of WechatUser 服务层************************************/


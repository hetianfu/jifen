<?php

namespace api\modules\seller\service;

use api\modules\seller\models\forms\AppVersionModel;
use api\modules\seller\models\forms\AppVersionQuery;
use fanyou\enums\QueryEnum;
use fanyou\tools\ArrayHelper;
use fanyou\models\base\SearchModel;

/**
 * Class AppVersionService
 * @package api\modules\seller\service
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-07-13
 */
class AppVersionService
{

/*********************AppVersion模块服务层************************************/
	/**
	 * 添加一条app更新管理
	 * @param AppVersionModel $model
     * @return mixed
     * @throws \Throwable
     */
	public function addAppVersion(AppVersionModel $model)
	{
		$model->insert();
		return $model->getPrimaryKey();
	}
	/**
	 * 分页获取列表
	 * @param AppVersionQuery $query
	 * @return array
	 * @throws \yii\web\NotFoundHttpException
	 */
	public function getAppVersionPage(AppVersionQuery $query): array
	{
		$searchModel = new SearchModel([
			'model' => AppVersionModel::class,
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
	 * 根据Id获取更新管理
	 * @param $id
	 * @return Object
	 */
	public function getOneById($id)
	{
		return AppVersionModel::findOne($id);
	}

	/**
	 * 根据Id更新更新管理
	 * @param AppVersionModel $model
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function updateAppVersionById (AppVersionModel $model): int
	{
		$model->setOldAttribute('id',$model->id);
		return $model->update();
	}
	/**
	 * 根据Id删除更新管理
	 * @param $id
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function deleteById ($id) : int
	{
		$model = AppVersionModel::findOne($id);
		return  $model->delete();
	}
}
/**********************End Of AppVersion 服务层************************************/


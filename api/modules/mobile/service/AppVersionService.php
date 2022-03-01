<?php

namespace api\modules\mobile\service;

use api\modules\mobile\models\forms\AppVersionModel;
use api\modules\mobile\models\forms\AppVersionQuery;
use fanyou\enums\NumberEnum;
use fanyou\enums\QueryEnum;
use fanyou\enums\StatusEnum;
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
	 * 分页获取列表
	 * @param AppVersionQuery $query
	 * @return array
	 * @throws \yii\web\NotFoundHttpException
	 */
	public function getLastVersion(AppVersionQuery $query)
	{   $query->limit=NumberEnum::ONE;
	    $query->status=StatusEnum::ENABLED;
		$searchModel = new SearchModel([
			'model' => AppVersionModel::class,
			'scenario' => 'default',
			'partialMatchAttributes' => ['app_name'], // 模糊查询
			'defaultOrder' => $query->getOrdersArray('id',SORT_DESC),
		]);
		$searchWord = $searchModel->search( $query->toArray([],[QueryEnum::LIMIT]) );
		return $searchWord->getModels();
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

}
/**********************End Of AppVersion 服务层************************************/


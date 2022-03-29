<?php

namespace api\modules\mobile\service;

use api\modules\mobile\models\forms\PagConfigModel;
use api\modules\mobile\models\forms\PagConfigQuery;
use fanyou\enums\QueryEnum;
use fanyou\tools\ArrayHelper;
use fanyou\models\base\SearchModel;

/**
 * Class PagConfigService
 * @package api\modules\seller\service
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-08-18
 */
class PagConfigService
{

/*********************PagConfig模块服务层************************************/

	/**
	 * 分页获取列表
	 * @param PagConfigQuery $query
	 * @return array
	 * @throws \yii\web\NotFoundHttpException
	 */
	public function getPagConfigPage(PagConfigQuery $query): array
	{
		$searchModel = new SearchModel([
			'model' => PagConfigModel::class,
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
	 * 根据Id获取置
	 * @param $title
	 * @return Object
	 */
	public function getOneByTitle($title)
	{
		return PagConfigModel::find()->where(['identify'=>$title])->one();
	}


}
/**********************End Of PagConfig 服务层************************************/


<?php

namespace api\modules\seller\service;

use api\modules\seller\models\forms\SmsLogModel;
use api\modules\seller\models\forms\SmsLogQuery;
use fanyou\enums\QueryEnum;
use fanyou\tools\ArrayHelper;
use fanyou\models\base\SearchModel;

/**
 * Class SmsLogService
 * @package api\modules\seller\service
 * @author: HenryJia
 * @E-mail: 316560925@qq.com
 * @date: 2020-11-16
 */
class SmsLogService
{

/*********************SmsLog模块服务层************************************/
	/**
	 * 添加一条短信记录
	 * @param SmsLogModel $model
     * @return mixed
     * @throws \Throwable
     */
	public function addSmsLog(SmsLogModel $model)
	{
		$model->insert();
		return $model->getPrimaryKey();
	}
	/**
	 * 分页获取列表
	 * @param SmsLogQuery $query
	 * @return array
	 * @throws \yii\web\NotFoundHttpException
	 */
	public function getSmsLogPage(SmsLogQuery $query): array
	{
		$searchModel = new SearchModel([
			'model' => SmsLogModel::class,
			'scenario' => 'default',
			'partialMatchAttributes' => ['telephone'], // 模糊查询
			'defaultOrder' => $query->getOrdersArray(),
			'pageSize' => $query->limit,
		]);
		$searchWord = $searchModel->search( $query->toArray([],[QueryEnum::LIMIT]) );
		$result['list'] = ArrayHelper::toArray($searchWord->getModels());
		$result['totalCount'] = $searchWord->pagination->totalCount;
		return $result;
	}

	/**
	 * 根据Id获取短信记录
	 * @param $id
	 * @return Object
	 */
	public function getOneById($id)
	{
		return SmsLogModel::findOne($id);
	}

	/**
	 * 根据Id更新短信记录
	 * @param SmsLogModel $model
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function updateSmsLogById (SmsLogModel $model): int
	{
		$model->setOldAttribute('id',$model->id);
		return $model->update();
	}
	/**
	 * 根据Id删除短信记录
	 * @param $id
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function deleteById ($id) : int
	{
		$model = SmsLogModel::findOne($id);
		return  $model->delete();
	}
}
/**********************End Of SmsLog 服务层************************************/


<?php

namespace fanyou\device\service;

use fanyou\device\hbh\DeviceCommonResultModel;
use fanyou\device\hbh\DeviceCommonResultQuery;
use fanyou\enums\QueryEnum;
use fanyou\tools\ArrayHelper;
use fanyou\models\base\SearchModel;

/**
 * Class DeviceCommonResultService
 * @package api\modules\seller\service
 * @author: HenryJia
 * @E-mail: 316560925@qq.com
 * @date: 2020-09-02
 */
class DeviceCommonResultService
{

/*********************DeviceCommonResult模块服务层************************************/
	/**
	 * 添加一条指令回调
	 * @param DeviceCommonResultModel $model
     * @return mixed
     * @throws \Throwable
     */
	public function addDeviceCommonResult(DeviceCommonResultModel $model)
	{
		$model->insert();
		return $model->getPrimaryKey();
	}
	/**
	 * 分页获取列表
	 * @param DeviceCommonResultQuery $query
	 * @return array
	 * @throws \yii\web\NotFoundHttpException
	 */
	public function getDeviceCommonResultPage(DeviceCommonResultQuery $query): array
	{
		$searchModel = new SearchModel([
			'model' => DeviceCommonResultModel::class,
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
	 * 根据Id获取指令回调
	 * @param $id
	 * @return Object
	 */
	public function getOneById($id)
	{
		return DeviceCommonResultModel::findOne($id);
	}

	/**
	 * 根据Id更新指令回调
	 * @param DeviceCommonResultModel $model
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function updateDeviceCommonResultById (DeviceCommonResultModel $model): int
	{
		$model->setOldAttribute('id',$model->id);
		return $model->update();
	}
	/**
	 * 根据Id删除指令回调
	 * @param $id
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function deleteById ($id) : int
	{
		$model = DeviceCommonResultModel::findOne($id);
		return  $model->delete();
	}
}
/**********************End Of DeviceCommonResult 服务层************************************/


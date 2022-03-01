<?php

namespace fanyou\device\service;

use fanyou\device\models\forms\DeviceInfoModel;
use fanyou\device\models\forms\DeviceInfoQuery;
use fanyou\enums\QueryEnum;
use fanyou\tools\ArrayHelper;
use fanyou\models\base\SearchModel;

/**
 * Class DeviceInfoService
 * @package api\modules\seller\service
 * @author: HenryJia
 * @E-mail: 316560925@qq.com
 * @date: 2020-09-02
 */
class DeviceInfoService
{

/*********************DeviceInfo模块服务层************************************/
	/**
	 * 添加一条设备信息
	 * @param DeviceInfoModel $model
     * @return mixed
     * @throws \Throwable
     */
	public function addDeviceInfo(DeviceInfoModel $model)
	{
		$model->insert();
		return $model->getPrimaryKey();
	}
	/**
	 * 分页获取列表
	 * @param DeviceInfoQuery $query
	 * @return array
	 * @throws \yii\web\NotFoundHttpException
	 */
	public function getDeviceInfoPage(DeviceInfoQuery $query): array
	{
		$searchModel = new SearchModel([
			'model' => DeviceInfoModel::class,
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
	 * 根据Id获取设备信息
	 * @param $id
	 * @return Object
	 */
	public function getOneById($id)
	{
		return DeviceInfoModel::findOne($id);
	}

	/**
	 * 根据Id更新设备信息
	 * @param DeviceInfoModel $model
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function updateDeviceInfoById (DeviceInfoModel $model): int
	{
		$model->setOldAttribute('id',$model->id);
		return $model->update();
	}
	/**
	 * 根据Id删除设备信息
	 * @param $id
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function deleteById ($id) : int
	{
		$model = DeviceInfoModel::findOne($id);
		return  $model->delete();
	}
}
/**********************End Of DeviceInfo 服务层************************************/


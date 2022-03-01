<?php

namespace  fanyou\device\service;

use fanyou\device\models\forms\DeviceUserModel;
use fanyou\device\models\forms\DeviceUserQuery;
use fanyou\enums\QueryEnum;
use fanyou\tools\ArrayHelper;
use fanyou\models\base\SearchModel;

/**
 * Class DeviceUserService
 * @package api\modules\seller\service
 * @author: HenryJia
 * @E-mail: 316560925@qq.com
 * @date: 2020-09-02
 */
class DeviceUserService
{

/*********************DeviceUser模块服务层************************************/
	/**
	 * 添加一条用户设备
	 * @param DeviceUserModel $model
     * @return mixed
     * @throws \Throwable
     */
	public function addDeviceUser(DeviceUserModel $model)
	{
		$model->insert();
		return $model->getPrimaryKey();
	}
	/**
	 * 分页获取列表
	 * @param DeviceUserQuery $query
	 * @return array
	 * @throws \yii\web\NotFoundHttpException
	 */
	public function getDeviceUserPage(DeviceUserQuery $query): array
	{
		$searchModel = new SearchModel([
			'model' => DeviceUserModel::class,
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
	 * 根据Id获取用户设备
	 * @param $id
	 * @return Object
	 */
	public function getOneById($id)
	{
		return DeviceUserModel::findOne($id);
	}

	/**
	 * 根据Id更新用户设备
	 * @param DeviceUserModel $model
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function updateDeviceUserById (DeviceUserModel $model): int
	{
		$model->setOldAttribute('id',$model->id);
		return $model->update();
	}
	/**
	 * 根据Id删除用户设备
	 * @param $id
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function deleteById ($id) : int
	{
		$model = DeviceUserModel::findOne($id);
		return  $model->delete();
	}
}
/**********************End Of DeviceUser 服务层************************************/


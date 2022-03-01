<?php

namespace fanyou\device\service;

use fanyou\device\DeviceService;
use fanyou\device\enums\CommandTypeEnum;
use fanyou\device\models\forms\DeviceCommonModel;
use fanyou\device\models\forms\DeviceCommonQuery;
use fanyou\enums\QueryEnum;
use fanyou\enums\StatusEnum;
use fanyou\tools\ArrayHelper;
use fanyou\models\base\SearchModel;

/**
 * Class DeviceCommonService
 * @package api\modules\seller\service
 * @author: HenryJia
 * @E-mail: 316560925@qq.com
 * @date: 2020-09-02
 */
class DeviceCommonService
{

/*********************DeviceCommon模块服务层************************************/
	/**
	 * 添加一条硬件指令
	 * @param DeviceCommonModel $model
     * @return mixed
     * @throws \Throwable
     */
	public function addDeviceCommon(DeviceCommonModel $model)
	{
		$model->insert();
		return $model->getPrimaryKey();
	}
	/**
	 * 分页获取列表
	 * @param DeviceCommonQuery $query
	 * @return array
	 * @throws \yii\web\NotFoundHttpException
	 */
	public function getDeviceCommonPage(DeviceCommonQuery $query): array
	{
		$searchModel = new SearchModel([
			'model' => DeviceCommonModel::class,
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
     * 查询是否在线指令
     * @return DeviceCommonModel|null
     */
    public function getQueryDeviceOnline ()
    {
        $command= DeviceCommonModel::findOne(['status'=>StatusEnum::ENABLED,'type'=>CommandTypeEnum::QUERY_ONLINE]);
        return $command;
    }
     /**
     * 查询设备列表
     * @return DeviceCommonModel|null
     */
    public function getQueryDeviceList ()
    {
        $command= DeviceCommonModel::findOne(['status'=>StatusEnum::ENABLED,'type'=>CommandTypeEnum::QUERY_LIST]);
        return $command;
    }
    /**
     * 激活设备
     * @return DeviceCommonModel|null
     */
    public function getActiveDevice ()
    {
        $command= DeviceCommonModel::findOne(['status'=>StatusEnum::ENABLED,'type'=>CommandTypeEnum::ADD]);
        return $command;
    }


	/**
	 * 根据Id获取硬件指令
	 * @param $id
	 * @return Object
	 */
	public function getOneById($id)
	{
		return DeviceCommonModel::findOne($id);
	}

	/**
	 * 根据Id更新硬件指令
	 * @param DeviceCommonModel $model
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function updateDeviceCommonById (DeviceCommonModel $model): int
	{
		$model->setOldAttribute('id',$model->id);
		return $model->update();
	}
	/**
	 * 根据Id删除硬件指令
	 * @param $id
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function deleteById ($id) : int
	{
		$model = DeviceCommonModel::findOne($id);
		return  $model->delete();
	}
}
/**********************End Of DeviceCommon 服务层************************************/


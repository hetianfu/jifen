<?php

namespace api\modules\seller\service;

use api\modules\seller\models\forms\MerchantInfoModel;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-05-08
 */
class MerchantInfoService
{

/*********************MerchantInfo模块服务层************************************/

	/**
	 * 根据Id获取置
	 * @param $id
	 * @return Object
	 */
	public function getOneById($id)
	{
		return MerchantInfoModel::findOne($id);
	}

    /**
     * 根据Id更新置
     * @param MerchantInfoModel $model
     * @return int
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
	public function updateMerchantInfoById (MerchantInfoModel $model): int
	{
		$model->setOldAttribute('id',$model->id);
		return $model->update();
	}
	/**
	 * 根据Id删除置
	 * @param $id
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function deleteById ($id) : int
	{
		$model = MerchantInfoModel::findOne($id);
		return  $model->delete();
	}
}
/**********************End Of MerchantInfo 服务层************************************/


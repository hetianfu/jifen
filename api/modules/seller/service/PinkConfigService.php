<?php

namespace api\modules\seller\service;

use api\modules\seller\models\forms\PinkConfigModel;
use api\modules\seller\models\forms\PinkConfigQuery;
use fanyou\enums\QueryEnum;
use fanyou\tools\ArrayHelper;
use fanyou\models\base\SearchModel;

/**
 * Class PinkConfigService
 * @package api\modules\seller\service
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-08-20
 */
class PinkConfigService
{

/*********************PinkConfig模块服务层************************************/
	/**
	 * 添加一条拼团配置表
	 * @param PinkConfigModel $model
     * @return mixed
     * @throws \Throwable
     */
	public function addPinkConfig(PinkConfigModel $model)
	{
		$model->insert();
		return $model->getPrimaryKey();
	}
	/**
	 * 分页获取列表
	 * @param PinkConfigQuery $query
	 * @return array
	 * @throws \yii\web\NotFoundHttpException
	 */
	public function getPinkConfigPage(PinkConfigQuery $query): array
	{
		$searchModel = new SearchModel([
			'model' => PinkConfigModel::class,
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
	 * 根据Id获取拼团配置表
	 * @param $id
	 * @return Object
	 */
	public function getOneById($id)
	{
		return PinkConfigModel::findOne($id);
	}
    /**
     * 根据商品Id取拼团配置表
     * @param $product
     * @return Object
     */
    public function getOneByProduct($product)
    {
        return PinkConfigModel::findOne(['product_id'=>$product]);
    }
	/**
	 * 根据Id更新拼团配置表
	 * @param PinkConfigModel $model
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function updatePinkConfigById (PinkConfigModel $model): int
	{
		$model->setOldAttribute('id',$model->id);
		return $model->update();
	}
	/**
	 * 根据Id删除拼团配置表
	 * @param $id
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function deleteById ($id) : int
	{
		$model = PinkConfigModel::findOne($id);
		return  $model->delete();
	}
}
/**********************End Of PinkConfig 服务层************************************/


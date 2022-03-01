<?php

namespace api\modules\seller\service;

use api\modules\seller\models\forms\PagConfigModel;
use api\modules\seller\models\forms\PagConfigQuery;
use fanyou\enums\QueryEnum;
use fanyou\enums\SystemConfigEnum;
use fanyou\tools\ArrayHelper;
use fanyou\models\base\SearchModel;
use Yii;

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
	 * 添加一条单页配置
	 * @param PagConfigModel $model
     * @return mixed
     * @throws \Throwable
     */
	public function addPagConfig(PagConfigModel $model)
	{
		$model->insert();
		return $model->getPrimaryKey();
	}
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
	 * @param $id
	 * @return Object
	 */
	public function getOneById($id)
	{
		return PagConfigModel::findOne($id);
	}

	/**
	 * 根据Id更新置
	 * @param PagConfigModel $model
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function updatePagConfigById (PagConfigModel $model): int
	{
        $title=PagConfigModel::find()->select(['identify'])->where(['id'=>$model->id])->asArray()->one()['identify'];
        //加入缓存
        $tokenId =SystemConfigEnum::REDIS_PAGE_TITLE.$title;
        Yii::$app->cache->delete($tokenId);

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
		$model = PagConfigModel::findOne($id);
		return  $model->delete();
	}
}
/**********************End Of PagConfig 服务层************************************/


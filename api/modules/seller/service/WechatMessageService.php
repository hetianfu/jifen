<?php

namespace api\modules\seller\service;

use api\modules\seller\models\forms\WechatMessageModel;
use api\modules\seller\models\forms\WechatMessageQuery;
use fanyou\enums\QueryEnum;
use fanyou\tools\ArrayHelper;
use fanyou\models\base\SearchModel;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-05-21
 */
class WechatMessageService
{

/*********************WechatMessage模块服务层************************************/
	/**
	 * 添加一条公众号配置
	 * @param WechatMessageModel $model
     * @return mixed
     * @throws \Throwable
     */
	public function addWechatMessage(WechatMessageModel $model)
	{
		$model->insert();
		return $model->getPrimaryKey();
	}
	/**
	 * 分页获取列表
	 * @param WechatMessageQuery $query
	 * @return array
	 * @throws \yii\web\NotFoundHttpException
	 */
	public function getWechatMessagePage(WechatMessageQuery $query): array
	{
		$searchModel = new SearchModel([
			'model' => WechatMessageModel::class,
			'scenario' => 'default',
			'partialMatchAttributes' => ['name'], // 模糊查询
			'defaultOrder' => [
			'id' => SORT_ASC
			],
			'pageSize' => $query->limit,
		]);
		$searchWord = $searchModel->search( $query->toArray([],[QueryEnum::LIMIT]) );
		$result['list'] = ArrayHelper::toArray($searchWord->getModels());
		$result['totalCount'] = $searchWord->pagination->totalCount;
		return $result;
	}

	/**
	 * 根据Id获取配置
	 * @param $id
	 * @return Object
	 */
	public function getOneById($id)
	{
		return WechatMessageModel::findOne($id);
	}

	/**
	 * 根据Id更新配置
	 * @param WechatMessageModel $model
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function updateWechatMessageById (WechatMessageModel $model): int
	{
		$model->setOldAttribute('id',$model->id);

		return $model->update();
	}
	/**
	 * 根据Id删除配置
	 * @param $id
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function deleteById ($id) : int
	{
		$model = WechatMessageModel::findOne($id);
		return  $model->delete();
	}
}
/**********************End Of WechatMessage 服务层************************************/


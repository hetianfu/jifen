<?php

namespace api\modules\mobile\service;

use api\modules\mobile\models\forms\RefundOrderApplyModel;
use api\modules\mobile\models\forms\RefundOrderApplyQuery;
use fanyou\tools\ArrayHelper;
use fanyou\models\base\SearchModel;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-05-21
 */
class RefundOrderApplyService
{

/*********************RefundOrderApply模块服务层************************************/
	/**
	 * 添加一条用户申请退单列表
	 * @param RefundOrderApplyModel $model
     * @return mixed
     * @throws \Throwable
     */
	public function addRefundOrderApply(RefundOrderApplyModel $model)
	{

		$model->insert();

		return $model->getPrimaryKey();
	}
	/**
	 * 分页获取列表
	 * @param RefundOrderApplyQuery $query
	 * @return array
	 * @throws \yii\web\NotFoundHttpException
	 */
	public function getRefundOrderApplyPage(RefundOrderApplyQuery $query): array
	{
		$searchModel = new SearchModel([
			'model' => RefundOrderApplyModel::class,
			'scenario' => 'default',
			'partialMatchAttributes' => ['name'], // 模糊查询
			'defaultOrder' => [
			'created_at' => SORT_DESC
			],
			'pageSize' => $query->limit,
		]);
		$searchWord = $searchModel->search(array_filter($query->toArray()));
		$result['list'] = ArrayHelper::toArray($searchWord->getModels());
		$result['totalCount'] = $searchWord->pagination->totalCount;
		return $result;
	}

	/**
	 * 根据Id获取请退单列表
	 * @param $id
	 * @return Object
	 */
	public function getOneById($id)
	{
		return RefundOrderApplyModel::findOne($id);
	}

	/**
	 * 根据Id更新请退单列表
	 * @param RefundOrderApplyModel $model
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function updateRefundOrderApplyById (RefundOrderApplyModel $model): int
	{
		$model->setOldAttribute('id',$model->id);
		return $model->update();
	}
	/**
	 * 根据Id删除请退单列表
	 * @param $id
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function deleteById ($id) : int
	{
		$model = RefundOrderApplyModel::findOne($id);
		return  $model->delete();
	}
}
/**********************End Of RefundOrderApply 服务层************************************/


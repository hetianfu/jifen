<?php

namespace api\modules\seller\service;

use api\modules\seller\models\forms\PrintModel;
use api\modules\seller\models\forms\PrintQuery;
use fanyou\tools\ArrayHelper;
use fanyou\models\base\SearchModel;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-04-02
 */
class PrintService
{

/*********************Print模块服务层************************************/
	/**
	 * 添加一条打印机
	 * @param PrintModel $model
     * @return mixed
     * @throws \Throwable
     */
	public function addPrint(PrintModel $model)
	{
		$model->insert();
		return $model->getPrimaryKey();
	}
	/**
	 * 分页获取列表
	 * @param PrintQuery $query
	 * @return array
	 * @throws \yii\web\NotFoundHttpException
	 */
	public function getPrintPage(PrintQuery $query): array
	{
		$searchModel = new SearchModel([
			'model' => PrintModel::class,
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
	 * 根据Id获取
	 * @param $id
	 * @return Object
	 */
	public function getOneById($id)
	{
		return PrintModel::findOne($id);
	}

	/**
	 * 根据Id更新
	 * @param PrintModel $model
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function updatePrintById (PrintModel $model): int
	{
		$model->setOldAttribute('id',$model->id);
		return $model->update();
	}
	/**
	 * 根据Id删除
	 * @param $id
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function deleteById ($id) : int
	{
		$model = PrintModel::findOne($id);
		return  $model->delete();
	}
}
/**********************End Of Print 服务层************************************/


<?php

namespace api\modules\seller\service;

use api\modules\seller\models\forms\FreightTemplateModel;
use api\modules\seller\models\forms\FreightTemplateQuery;
use fanyou\enums\QueryEnum;
use fanyou\tools\ArrayHelper;
use fanyou\models\base\SearchModel;

/**
 * Class FreightTemplateService
 * @package api\modules\seller\service
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-07-29
 */
class FreightTemplateService
{

/*********************FreightTemplate模块服务层************************************/
	/**
	 * 添加一条运费模版
	 * @param FreightTemplateModel $model
     * @return mixed
     * @throws \Throwable
     */
	public function addFreightTemplate(FreightTemplateModel $model)
	{
		$model->insert();
		return $model->getPrimaryKey();
	}
	/**
	 * 分页获取列表
	 * @param FreightTemplateQuery $query
	 * @return array
	 * @throws \yii\web\NotFoundHttpException
	 */
	public function getFreightTemplatePage(FreightTemplateQuery $query): array
	{
		$searchModel = new SearchModel([
			'model' => FreightTemplateModel::class,
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
    public function getFreightTemplateList(FreightTemplateQuery $query): array
    {
        $searchModel = new SearchModel([
            'model' => FreightTemplateModel::class,
            'scenario' => 'default',
            'partialMatchAttributes' => ['name'], // 模糊查询
            'defaultOrder' => $query->getOrdersArray(),
        ]);
        $searchWord = $searchModel->search( $query->toArray() );
        return ArrayHelper::toArray($searchWord->getModels());
    }
	/**
	 * 根据Id获取版
	 * @param $id
	 * @return Object
	 */
	public function getOneById($id)
	{
		return FreightTemplateModel::findOne($id);
	}

	/**
	 * 根据Id更新版
	 * @param FreightTemplateModel $model
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function updateFreightTemplateById (FreightTemplateModel $model): int
	{
		$model->setOldAttribute('id',$model->id);
		return $model->update();
	}
	/**
	 * 根据Id删除版
	 * @param $id
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function deleteById ($id) : int
	{
		$model = FreightTemplateModel::findOne($id);
		return  $model->delete();
	}
}
/**********************End Of FreightTemplate 服务层************************************/


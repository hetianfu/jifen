<?php

namespace api\modules\seller\service;

use api\modules\seller\models\forms\UnitModel;
use api\modules\seller\models\forms\UnitQuery;
use fanyou\enums\QueryEnum;
use fanyou\tools\ArrayHelper;
use fanyou\models\base\SearchModel;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-03-23
 */
class UnitService
{

/*********************Unit模块服务层************************************/
	/**
	 * 添加一条EX_单位
	 * @param UnitModel $model
     * @return mixed
     * @throws \Throwable
     */
	public function addUnit(UnitModel $model)
	{
		$model->insert();
		return $model->getPrimaryKey();
	}
	/**
	 * 分页获取列表
	 * @param UnitQuery $query
	 * @return array
	 * @throws \yii\web\NotFoundHttpException
	 */
	public function getUnitPage(UnitQuery $query): array
	{
		$searchModel = new SearchModel([
			'model' => UnitModel::class,
			'scenario' => 'default',
			'partialMatchAttributes' => ['name'], // 模糊查询
			'defaultOrder' =>$query->getOrdersArray() ,
			'pageSize' => $query->limit,
		]);

		$searchWord = $searchModel->search($query->toArray([],[QueryEnum::LIMIT]) );
		$result['list'] = ArrayHelper::toArray($searchWord->getModels());
		$result['totalCount'] = $searchWord->pagination->totalCount;
		return $result;
	}
    public function getUnitList(UnitQuery $query): array
    {
        $searchModel = new SearchModel([
            'model' => UnitModel::class,
            'scenario' => 'default',
            'partialMatchAttributes' => ['name'], // 模糊查询
            'defaultOrder' =>$query->getOrdersArray() ,
        ]);
        $searchWord = $searchModel->search( $query->toArray() );
        return ArrayHelper::toArray($searchWord->getModels());
    }

	/**
	 * 根据Id获取单位
	 * @param $id
	 * @return Object
	 */
	public function getOneById($id)
	{
		return UnitModel::findOne($id);
	}

	/**
	 * 根据Id更新单位
	 * @param UnitModel $model
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function updateUnitById (UnitModel $model): int
	{
		$model->setOldAttribute('id',$model->id);
		return $model->update();
	}
	/**
	 * 根据Id删除单位
	 * @param $id
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function deleteById ($id) : int
	{
		$model = UnitModel::findOne($id);
		return  $model->delete();
	}
}
/**********************End Of Unit 服务层************************************/


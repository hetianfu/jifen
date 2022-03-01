<?php

namespace api\modules\seller\service;

use api\modules\seller\models\forms\InformationCategoryModel;
use api\modules\seller\models\forms\InformationCategoryQuery;
use api\modules\seller\models\forms\InformationModel;
use api\modules\seller\models\forms\InformationQuery;
use fanyou\enums\QueryEnum;
use fanyou\enums\SortEnum;
use fanyou\tools\ArrayHelper;
use fanyou\models\base\SearchModel;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-06-13
 */
class InformationService
{

/*********************Information模块服务层************************************/
	/**
	 * 添加一条资讯模块
	 * @param InformationModel $model
     * @return mixed
     * @throws \Throwable
     */
	public function addInformation(InformationModel $model)
	{
		$model->insert();
		return $model->getPrimaryKey();
	}
	/**
	 * 分页获取列表
	 * @param InformationQuery $query
	 * @return array
	 * @throws \yii\web\NotFoundHttpException
	 */
	public function getInformationPage(InformationQuery $query): array
	{
		$searchModel = new SearchModel([
			'model' => InformationModel::class,
			'scenario' => 'default',
			'partialMatchAttributes' => ['title'], // 模糊查询
			'defaultOrder' => $query->getOrdersArray(SortEnum::SHOW_ORDER),
			'pageSize' => $query->limit,
		]);

		$searchWord = $searchModel->search( $query->toArray([],[QueryEnum::LIMIT]) );
		$result['list'] = ArrayHelper::toArray($searchWord->getModels());
		$result['totalCount'] = $searchWord->pagination->totalCount;

		return $result;
	}

    /**
     * 统计数量
     * @param InformationQuery $query
     * @return mixed
     * @throws \yii\web\NotFoundHttpException
     */
    public function count(InformationQuery $query)
    {
        $searchModel = new SearchModel([
            'model' => InformationModel::class,
            'scenario' => 'default',
            'partialMatchAttributes' => ['title'], // 模糊查询
            'defaultOrder' => $query->getOrdersArray(SortEnum::SHOW_ORDER),
        ]);
        $searchWord = $searchModel->search( $query->toArray() );
        return $searchWord->query->count();
    }
	/**
	 * 根据Id获取块
	 * @param $id
	 * @return Object
	 */
	public function getOneById($id)
	{

		return InformationModel::findOne($id);
	}

	/**
	 * 根据Id更新块
	 * @param InformationModel $model
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function updateInformationById (InformationModel $model): int
	{
		$model->setOldAttribute('id',$model->id);
		return $model->update();
	}
	/**
	 * 根据Id删除块
	 * @param $id
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function deleteById ($id) : int
	{
		$model = InformationModel::findOne(['id'=>$id]);
		return  $model->delete();
	}

/***************************资讯分类************************************/
    /**
     * 添加一条资讯模块
     * @param InformationCategoryModel $model
     * @return mixed
     * @throws \Throwable
     */
    public function addInformationCategory(InformationCategoryModel $model)
    {
        $model->insert();
        return $model->getPrimaryKey();
    }

    public function getOneCategoryById($id)
    {
        return InformationCategoryModel::findOne($id);
    }

    public function updateInformationCategoryById (InformationCategoryModel $model): int
    {
        $model->setOldAttribute('id',$model->id);
        return $model->update();
    }

    /**
     * 根据Id删分类
     * @param $id
     * @return int
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function deleteCategoryById ($id) : int
    {
        $model = InformationCategoryModel::findOne($id);
        return  $model->delete();
    }

    /**
     * 统计分类数量
     * @param InformationCategoryQuery $query
     * @return mixed
     * @throws \yii\web\NotFoundHttpException
     */
    public function countCategory(InformationCategoryQuery $query)
    {
        $searchModel = new SearchModel([
            'model' => InformationCategoryModel::class,
            'scenario' => 'default',
            'partialMatchAttributes' => ['title'], // 模糊查询
            'defaultOrder' => $query->getOrdersArray(SortEnum::SHOW_ORDER),
        ]);
        $searchWord = $searchModel->search( $query->toArray() );
        return $searchWord->query->count();
    }

    public function getInformationCategoryPage(InformationCategoryQuery $query): array
    {
        $searchModel = new SearchModel([
            'model' => InformationCategoryModel::class,
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
    public function getInformationCategoryList(InformationCategoryQuery $query): array
    {
        $searchModel = new SearchModel([
            'model' => InformationCategoryModel::class,
            'scenario' => 'default',
            'partialMatchAttributes' => ['name'], // 模糊查询
            'defaultOrder' => $query->getOrdersArray(),
        ]);
        $searchWord = $searchModel->search( $query->toArray() );
        return ArrayHelper::toArray($searchWord->getModels());
    }

}
/**********************End Of Information 服务层************************************/


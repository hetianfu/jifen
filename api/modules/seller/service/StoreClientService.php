<?php

namespace api\modules\seller\service;

use api\modules\seller\models\forms\StoreClientCategoryModel;
use api\modules\seller\models\forms\StoreClientCategoryQuery;
use api\modules\seller\models\forms\StoreClientModel;
use api\modules\seller\models\forms\StoreClientQuery;
use fanyou\enums\QueryEnum;
use fanyou\tools\ArrayHelper;
use fanyou\models\base\SearchModel;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-06-13
 */
class StoreClientService
{

    /*********************StoreClientCategory模块服务层************************************/
    /**
     * 添加一条对象管理分类
     * @param StoreClientCategoryModel $model
     * @return mixed
     * @throws \Throwable
     */
    public function addStoreClientCategory(StoreClientCategoryModel $model)
    {
        $model->insert();
        return $model->getPrimaryKey();
    }

    /**
     * 分页获取列表
     * @param StoreClientCategoryQuery $query
     * @return array
     * @throws \yii\web\NotFoundHttpException
     */
    public function getStoreClientCategoryPage(StoreClientCategoryQuery $query): array
    {
        $searchModel = new SearchModel([
            'model' => StoreClientCategoryModel::class,
            'scenario' => 'default',
            'partialMatchAttributes' => ['name'], // 模糊查询
            'defaultOrder' => $query->getOrdersArray(),
            'pageSize' => $query->limit,
        ]);
        $searchWord = $searchModel->search($query->toArray([], [QueryEnum::LIMIT]));
        $result['list'] = ArrayHelper::toArray($searchWord->getModels());
        $result['totalCount'] = $searchWord->pagination->totalCount;
        return $result;
    }

    public function getStoreClientCategoryList(StoreClientCategoryQuery $query): array
    {
        $searchModel = new SearchModel([
            'model' => StoreClientCategoryModel::class,
            'scenario' => 'default',
            'partialMatchAttributes' => ['name'], // 模糊查询
            'defaultOrder' => $query->getOrdersArray(),
            'pageSize' => $query->limit,
        ]);
        $searchWord = $searchModel->search($query->toArray());

        return ArrayHelper::toArray($searchWord->getModels());
    }

    /**
     * 根据Id获取理分类
     * @param $id
     * @return Object
     */
    public function getCategoryById($id)
    {
        return StoreClientCategoryModel::findOne($id);
    }
    /**
     * 根据Id更新理分类
     * @param StoreClientCategoryModel $model
     * @return int
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function updateStoreClientCategoryById (StoreClientCategoryModel $model): int
    {
        $model->setOldAttribute('id',$model->id);
        return $model->update();
    }
    /**
     * 根据Id删除理分类
     * @param $id
     * @return int
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function deleteCategoryById ($id) : int
    {
        $model = StoreClientCategoryModel::findOne($id);
        return  $model->delete();
    }

/*********************StoreClient模块服务层************************************/
	/**
	 * 添加一条对象管理
	 * @param StoreClientModel $model
     * @return mixed
     * @throws \Throwable
     */
	public function addStoreClient(StoreClientModel $model)
	{
		$model->insert();
		return $model->getPrimaryKey();
	}
	/**
	 * 分页获取列表
	 * @param StoreClientQuery $query
	 * @return array
	 * @throws \yii\web\NotFoundHttpException
	 */
	public function getStoreClientPage(StoreClientQuery $query): array
	{
		$searchModel = new SearchModel([
			'model' => StoreClientModel::class,
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
	 * 根据Id获取理
	 * @param $id
	 * @return Object
	 */
	public function getOneById($id)
	{
		return StoreClientModel::findOne($id);
	}

	/**
	 * 根据Id更新理
	 * @param StoreClientModel $model
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function updateStoreClientById (StoreClientModel $model): int
	{
		$model->setOldAttribute('id',$model->id);
		return $model->update();
	}
	/**
	 * 根据Id删除理
	 * @param $id
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function deleteById ($id) : int
	{
		$model = StoreClientModel::findOne($id);
		return  $model->delete();
	}
}
/**********************End Of StoreClient 服务层************************************/


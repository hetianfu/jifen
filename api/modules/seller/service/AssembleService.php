<?php

namespace api\modules\seller\service;

use api\modules\seller\models\forms\ProductAssembleConfigModel;
use api\modules\seller\models\forms\ProductAssembleConfigQuery;
use api\modules\seller\models\forms\ProductAssembleModel;
use api\modules\seller\models\forms\ProductAssembleQuery;
use fanyou\tools\ArrayHelper;
use fanyou\models\base\SearchModel;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-04-26
 */
class  AssembleService
{

/*********************ProductAssembleConfig模块服务层************************************/
	/**
	 * 添加一条商品拼团配置表
	 * @param ProductAssembleConfigModel $model
     * @return mixed
     * @throws \Throwable
     */
	public function addProductAssembleConfig(ProductAssembleConfigModel $model)
	{
		$model->insert();
		return $model->getPrimaryKey();
	}
	/**
	 * 分页获取列表
	 * @param ProductAssembleConfigQuery $query
	 * @return array
	 * @throws \yii\web\NotFoundHttpException
	 */
	public function getProductAssembleConfigPage(ProductAssembleConfigQuery $query): array
	{
		$searchModel = new SearchModel([
			'model' => ProductAssembleConfigModel::class,
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
	 * 根据Id获取团配置表
	 * @param $id
	 * @return Object
	 */
	public function getOneById($id)
	{
		return ProductAssembleConfigModel::findOne($id);
	}

	/**
	 * 根据Id更新团配置表
	 * @param ProductAssembleConfigModel $model
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function updateProductAssembleConfigById (ProductAssembleConfigModel $model): int
	{
		$model->setOldAttribute('id',$model->id);
		return $model->update();
	}
	/**
	 * 根据Id删除团配置表
	 * @param $id
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function deleteById ($id) : int
	{
		$model = ProductAssembleConfigModel::findOne($id);
		return  $model->delete();
	}

    /*********************ProductAssemble模块服务层************************************/
    /**
     * 添加一条用户拼团表
     * @param ProductAssembleModel $model
     * @return mixed
     * @throws \Throwable
     */
    public function addProductAssemble(ProductAssembleModel $model)
    {
        $model->insert();
        return $model->getPrimaryKey();
    }
    /**
     * 分页获取列表
     * @param ProductAssembleQuery $query
     * @return array
     * @throws \yii\web\NotFoundHttpException
     */
    public function getProductAssemblePage(ProductAssembleQuery $query): array
    {
        $searchModel = new SearchModel([
            'model' => ProductAssembleModel::class,
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
     * 根据Id获取团表
     * @param $id
     * @return Object
     */
    public function getOneProductAssembleById($id)
    {
        return ProductAssembleModel::findOne($id);
    }

    /**
     * 根据Id更新团表
     * @param ProductAssembleModel $model
     * @return int
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function updateProductAssembleById (ProductAssembleModel $model): int
    {
        $model->setOldAttribute('id',$model->id);
        return $model->update();
    }
    /**
     * 根据Id删除团表
     * @param $id
     * @return int
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function deleteProductAssembleById ($id) : int
    {
        $model = ProductAssembleModel::findOne($id);
        return  $model->delete();
    }
}
/**********************End Of ProductAssembleConfig 服务层************************************/


<?php

namespace api\modules\seller\service;

use api\modules\seller\models\forms\ProductCategoryModel;
use api\modules\seller\models\forms\ProductCategoryQuery;
use fanyou\enums\QueryEnum;
use fanyou\tools\ArrayHelper;
use fanyou\models\base\SearchModel;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-06-09
 */
class ProductCategoryService
{

/*********************ProductCategory模块服务层************************************/
	/**
	 * 添加一条商品分类关联
	 * @param ProductCategoryModel $model
     * @return mixed
     * @throws \Throwable
     */
	public function addProductCategory(ProductCategoryModel $model)
	{
		$model->insert();
		return $model->getPrimaryKey();
	}
	/**
	 * 分页获取列表
	 * @param ProductCategoryQuery $query
	 * @return array
	 * @throws \yii\web\NotFoundHttpException
	 */
	public function getProductCategoryPage(ProductCategoryQuery $query): array
	{
		$searchModel = new SearchModel([
			'model' => ProductCategoryModel::class,
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
     * 获取商品ids
     * @param ProductCategoryQuery $query
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function getProductIds(ProductCategoryQuery $query)
    {
        $searchModel = new SearchModel([
            'model' => ProductCategoryModel::class,
            'scenario' => 'default',
            'partialMatchAttributes' => ['name'], // 模糊查询
            'select'=>[ 'product_id'],
            'distinct'=>true,
            'defaultOrder' => $query->getOrdersArray("id",SORT_DESC),
        ]);
        $searchWord = $searchModel->search( $query->toArray() );
        if(empty($searchWord->getModels())){
            return null;
        }
        return implode(',',array_column($searchWord->getModels(),'product_id') ) ;
    }
	/**
	 * 根据Id获取类关联
	 * @param $id
	 * @return Object
	 */
	public function getOneById($id)
	{
		return ProductCategoryModel::findOne($id);
	}

	/**
	 * 根据Id更新类关联
	 * @param ProductCategoryModel $model
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function updateProductCategoryById (ProductCategoryModel $model): int
	{
		$model->setOldAttribute('id',$model->id);
		return $model->update();
	}
	/**
	 * 根据Id删除类关联
	 * @param $id
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function deleteById ($id) : int
	{
		$model = ProductCategoryModel::findOne($id);
		return  $model->delete();
	}
}
/**********************End Of ProductCategory 服务层************************************/


<?php

namespace api\modules\mobile\service;

use api\modules\mobile\models\forms\ProductCategoryModel;
use api\modules\mobile\models\forms\ProductCategoryQuery;
use fanyou\enums\QueryEnum;
use fanyou\enums\StatusEnum;
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
            'defaultOrder' => $query->getOrdersArray(),
        ]);
        $searchWord = $searchModel->search( $query->toArray() );
        $ids=array_column($searchWord->getModels(),'product_id');
        return implode(',',array_unique($ids) ) ;
    }

    /**
     * 根据分类Id获取可展示的商品的Ids
     * @param $categoryId
     * @param int $show
     * @return string
     * @throws \yii\db\Exception
     */
    public function getProductIdsByCategoryId($categoryId,$show=StatusEnum::ENABLED)
    {
        $sql = 'select  product_id , category_id   from  `rf_product_category`  
        A left join `rf_category` B on A.`category_id`=B.`id` where B.`status`='.$show.' and A.`category_id` in ( '.$categoryId.')';
        $createCommand = \Yii::$app->db->createCommand($sql );
        $data = $createCommand->queryAll();
        return implode(',',array_column(ArrayHelper::toArray($data),'product_id') ) ;
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


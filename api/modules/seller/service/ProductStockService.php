<?php

namespace api\modules\seller\service;

use api\modules\seller\models\forms\ProductModel;
use api\modules\seller\models\forms\ProductSkuModel;
use api\modules\seller\models\forms\ProductStockDetailModel;
use api\modules\seller\models\forms\ProductStockDetailQuery;
use api\modules\seller\models\forms\ProductStockModel;
use api\modules\seller\models\forms\ProductStockQuery;
use fanyou\enums\QueryEnum;
use fanyou\models\base\SearchModel;
use fanyou\tools\ArrayHelper;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-05-08
 */
class ProductStockService
{
    private $productService;
    public function __construct()
    {
        $this->productService = new ProductService();

    }
/*********************ProductStock模块服务层************************************/
	/**
	 * 添加一条商品库存汇总
	 * @param ProductStockModel $model
     * @return mixed
     * @throws \Throwable
     */
	public function addProductStock(ProductStockModel $model)
	{
		$model->insert();
		return $model->getPrimaryKey();
	}
	/**
	 * 分页获取列表
	 * @param ProductStockQuery $query
	 * @return array
	 * @throws \yii\web\NotFoundHttpException
	 */
	public function getProductStockPage(ProductStockQuery $query): array
	{
//        if(!empty($query->categoryId)){
//            $query->product_id= QueryEnum::IN. implode(",",$this->productService->getProductIdByCategoryId($query->categoryId));
//        }

		$searchModel = new SearchModel([
			'model' => ProductSkuModel::class,
			'scenario' => 'default',
			'partialMatchAttributes' => ['name'], // 模糊查询
			'defaultOrder' => [
			'created_at' => SORT_DESC
			],
            'select' => ['id','product_id','stock_number','spec_snap','images','updated_at' ],
			'pageSize' => $query->limit,
		]);
		$searchWord = $searchModel->search( $query->toArray([],['limit']) );
		$list=ArrayHelper::toArray($searchWord->getModels());
        if(!empty($list)){
        foreach ($list as $k=>$value) {
            $p=  ProductModel::find()->select(['name'])->where(['id'=>$value['product_id']])->one();
            $list[$k]['name']=$p['name'];
		}
        }
		$result['list'] = $list;
		$result['totalCount'] = $searchWord->pagination->totalCount;
		return $result;
	}

	/**
	 * 根据Id获取存汇总
	 * @param $id
	 * @return Object
	 */
	public function getOneById($id)
	{
		return ProductStockModel::findOne($id);
	}

	/**
	 * 根据Id更新存汇总
	 * @param ProductStockModel $model
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function updateProductStockById (ProductStockModel $model): int
	{
		$model->setOldAttribute('id',$model->id);
		return $model->update();
	}
	/**
	 * 根据Id删除存汇总
	 * @param $id
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function deleteById ($id) : int
	{
		$model = ProductStockModel::findOne($id);
		return  $model->delete();
	}



    /*********************ProductStockDetail模块服务层************************************/
    /**
     * 添加一条商品库存详情
     * @param ProductStockDetailModel $model
     * @return mixed
     * @throws \Throwable
     */
    public function addProductStockDetail(ProductStockDetailModel $model)
    {
        $model->stock_number=$model->stock_number*$model->sub_type;
        $model->insert();
        return $model->getPrimaryKey();
    }
    /**
     * 分页获取列表
     * @param ProductStockDetailQuery $query
     * @return array
     * @throws \yii\web\NotFoundHttpException
     */
    public function getProductStockDetailPage(ProductStockDetailQuery $query): array
    {
        $searchModel = new SearchModel([
            'model' => ProductStockDetailModel::class,
            'scenario' => 'default',
            'partialMatchAttributes' => ['name'], // 模糊查询
            'defaultOrder' => $query->getOrdersArray(),
        ]);
        $searchWord = $searchModel->search( $query->toArray([],[QueryEnum::LIMIT]) );
        $result['list'] = ArrayHelper::toArray($searchWord->getModels());
        $result['totalCount'] = $searchWord->pagination->totalCount;
        return $result;
    }

    /**
     * 销售排行
     * @param ProductStockDetailQuery $query
     * @return array
     * @throws \yii\web\NotFoundHttpException
     */
    public function getProductStockRank(ProductStockDetailQuery $query): array
    {
        $searchModel = new SearchModel([
            'model' => ProductStockDetailModel::class,
            'scenario' => 'default',
            'select'=>['stock_number'=>'SUM(stock_number)','product_id','product_name'],
            'partialMatchAttributes' => ['name'], // 模糊查询
            'defaultOrder' =>$query->getOrdersArray('stock_number',SORT_DESC),
            'pageSize' => $query->limit,
            'groupBy'=>['product_id']

        ]);
        $searchWord = $searchModel->search($query->toArray([],[QueryEnum::LIMIT]));
        $result['list'] = ArrayHelper::toArray($searchWord->getModels());
        $result['totalCount'] = $searchWord->pagination->totalCount;
        return $result;
    }
    /**
     * 根据Id获取存详情
     * @param $id
     * @return Object
     */
    public function getStockDetailById($id)
    {
        return ProductStockDetailModel::findOne($id);
    }

    /**
     * 获取商品的售出最近十条订单号
     * @param $productId
     * @param int $page
     * @param int $limit
     * @return \yii\db\ActiveQuery
     */
    public function getDistinctOrderByProductId(  ProductStockDetailQuery $query)
    {
        $searchModel = new SearchModel([
            'model' => ProductStockDetailModel::class,
            'scenario' => 'default',
            'select'=>['order_id'],
            'partialMatchAttributes' => ['name'], // 模糊查询
            'defaultOrder' =>$query->getOrdersArray('id',SORT_DESC),

            'distinct'=>true,

        ]);
        $searchWord = $searchModel->search($query->toArray([],[QueryEnum::LIMIT]));

        $result['list'] = ArrayHelper::toArray($searchWord->getModels());
        $result['totalCount'] = $searchWord->pagination->totalCount;

        return $result;


//        return  ProductStockDetailModel::find()->select(['order_id'])->distinct()
//            ->where(['product_id'=>$productId])
//            ->offset($page)
//            ->limit($limit)
//            ->orderBy(['id'=>SORT_DESC])->asArray()->all();
    }


    /**
     * 根据Id更新存详情
     * @param ProductStockDetailModel $model
     * @return int
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function updateProductStockDetailById (ProductStockDetailModel $model): int
    {
        $model->setOldAttribute('id',$model->id);
        return $model->update();
    }
    /**
     * 根据Id删除存详情
     * @param $id
     * @return int
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function deleteStockDetailById ($id) : int
    {
        $model = ProductStockDetailModel::findOne($id);
        return  $model->delete();
    }

    public function sumStockDetailAmount(ProductStockDetailQuery $query)
    {
        $searchModel = new SearchModel([
            'model' => ProductStockDetailModel::class,
            'scenario' => 'default',
            'partialMatchAttributes' => ['name'], // 模糊查询
            'defaultOrder' => [
                'created_at' => SORT_DESC
            ],
            'select' => ['stock_number'=>'SUM(stock_number)','cost_amount'=>'SUM(cost_amount)','sales_amount'=>'SUM(sales_amount)'],
        ]);
        $searchWord = $searchModel->search($query->toArray() );
        return $searchWord->query->one();
    }
}
/**********************End Of ProductStock 服务层************************************/


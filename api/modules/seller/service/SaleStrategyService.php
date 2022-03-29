<?php

namespace api\modules\seller\service;

use api\modules\seller\models\forms\SaleProductModel;
use api\modules\seller\models\forms\SaleProductQuery;
use api\modules\seller\models\forms\SaleProductStrategyModel;
use api\modules\seller\models\forms\SaleProductStrategyQuery;
use api\modules\seller\models\forms\SaleStrategyModel;
use api\modules\seller\models\forms\SaleStrategyQuery;
use fanyou\enums\QueryEnum;
use fanyou\enums\SortEnum;
use fanyou\models\base\SearchModel;
use fanyou\tools\ArrayHelper;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-05-07
 */
class SaleStrategyService  extends  BasicService
{
    private $productService;
    public function __construct()
    {
        $this->productService = new ProductService();
    }

/*********************SaleStrategy模块服务层************************************/
	/**
	 * 添加一条商品秒杀配置
	 * @param SaleStrategyModel $model
     * @return mixed
     * @throws \Throwable
     */
	public function addSaleStrategy(SaleStrategyModel $model)
	{
		$model->insert();
		return $model->getPrimaryKey();
	}
	/**
	 * 分页获取列表
	 * @param SaleStrategyQuery $query
	 * @return array
	 * @throws \yii\web\NotFoundHttpException
	 */
	public function getSaleStrategyPage(SaleStrategyQuery $query): array
	{
		$searchModel = new SearchModel([
			'model' => SaleStrategyModel::class,
			'scenario' => 'default',
			'partialMatchAttributes' => ['name'], // 模糊查询
			'defaultOrder' => [
			'created_at' => SORT_DESC
			],
			'pageSize' => $query->limit,
		]);
		$searchWord = $searchModel->search( $query->toArray() );
		$result['list'] = ArrayHelper::toArray($searchWord->getModels());
		$result['totalCount'] = $searchWord->pagination->totalCount;
		return $result;
	}

	/**
	 * 根据Id获取杀配置
	 * @param $id
	 * @return Object
	 */
	public function getOneById($id)
	{
		return SaleStrategyModel::findOne($id);
	}

	/**
	 * 根据Id更新杀配置
	 * @param SaleStrategyModel $model
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function updateSaleStrategyById (SaleStrategyModel $model): int
	{
		$model->setOldAttribute('id',$model->id);
		return $model->update();
	}
	/**
	 * 根据Id删除杀配置
	 * @param $id
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function deleteById ($id) : int
	{
		$model = SaleStrategyModel::findOne($id);
		return  $model->delete();
	}

    /*********************SaleProduct模块服务层************************************/
    /**
     * 添加一条促销商品
     * @param SaleProductModel $model
     * @return mixed
     * @throws \Throwable
     */
    public function addSaleProduct(SaleProductModel $model)
    {
        $model->insert();
        $result = $model->getPrimaryKey();
        return $result;
    }
    /**
     * 分页获取列表
     * @param SaleProductQuery $query
     * @return array
     * @throws \yii\web\NotFoundHttpException
     */
    public function getSaleProductPage(SaleProductQuery $query): array
    {
        $searchModel = new SearchModel([
            'model' => SaleProductModel::class,
            'scenario' => 'default',
            'partialMatchAttributes' => ['name'], // 模糊查询
            'defaultOrder' => $query->getOrdersArray(SortEnum::SHOW_ORDER,SORT_ASC),
            'pageSize' => $query->limit,
        ]);
        $searchWord = $searchModel->search($query->toArray([],[QueryEnum::LIMIT]));
        $result['list'] = ArrayHelper::toArray($searchWord->getModels());
        $result['totalCount'] = $searchWord->pagination->totalCount;
        return $result;
    }

    /**
     * 根据Id获取品
     * @param $id
     * @return Object
     */
    public function getSaleProductById($id)
    {
        return SaleProductModel::findOne($id);
    }

    /**
     * 根据Id更新品
     * @param SaleProductModel $model
     * @return int
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function updateSaleProductById (SaleProductModel $model): int
    {
        $model->setOldAttribute('id',$model->id);
        return $model->update();
    }
    /**
     * 根据Id删除品
     * @param $id
     * @return int
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function deleteSaleProductById ($id) : int
    {   $result=0;
        $model = SaleProductModel::findOne($id);
        if(!empty($model)){
            $result=$model->delete();
            if($result){
            SaleProductStrategyModel::deleteAll(['product_id'=>$id]);
            }
        }
        return  $result;
    }

    /**
     * 校验，是否可以添加活动
     * @param $id
     * @return bool
     */
    public function verifySaleProductAdd($id): bool
    {
        return  empty($this->getSaleProductById($id))? true:false;
    }

    /**
     * 批量添加活动商品
     * @param array $rows
     * @param $strategyType
     * @param int $isSku
     * @return int
     * @throws \yii\db\Exception
     */
    public function batchAddProductStrategy( array $rows,$strategyType,$isSku=1)
    {
        // 批量写入数据
        $field = ['id','product_id','bar_code','spec_snap',  'strategy_type', 'cost_price', 'member_price', 'origin_price','sale_price',
            'limit_number', 'stock_number',   'sale_number','vir_sale_number','created_at', 'updated_at' ];// 'created_at', 'updated_at
        sort($field);
        $now=time();
        foreach ($rows as $k=>$v){

            $tagDetailSnap[]=$v;
           // $v['id']=parent::getRandomId();
            //$v['vir_sale_number']=1000;
            $v['strategy_type']=$strategyType;
            $v['saleNumber']=$v['virSaleNumber'];
            $v['limitNumber']=99;
            $v['createdAt']=$now;
            $v['updatedAt']=$now;
            unset( $v['image']);
            ksort($v) ;
            $rows[$k]= $v;
        }

        !empty($rows) && parent::DbCommand()->batchInsert(SaleProductStrategyModel::tableName(), $field, $rows)->execute();
        return count($rows);
    }


    /**
     * 分页获取列表
     * @param SaleProductStrategyQuery $query
     * @return array
     * @throws \yii\web\NotFoundHttpException
     */
    public function getSaleProductStrategyList(SaleProductStrategyQuery $query): array
    {
        $searchModel = new SearchModel([
            'model' => SaleProductStrategyModel::class,
            'scenario' => 'default',
            'partialMatchAttributes' => ['name'], // 模糊查询
            'defaultOrder' => [
                'created_at' => SORT_DESC
            ],
        ]);
        $searchWord = $searchModel->search( $query->toArray() );
        return ArrayHelper::toArray($searchWord->getModels());;
    }
    /**
     * 根据Id更新动策略
     * @param SaleProductStrategyModel $model
     * @return int
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function updateSaleProductStrategyById (SaleProductStrategyModel $model): int
    {
        $model->setOldAttribute('id',$model->id);
        return $model->update();
    }
}
/**********************End Of SaleStrategy 服务层************************************/


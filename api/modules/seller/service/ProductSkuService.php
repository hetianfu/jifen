<?php

namespace api\modules\seller\service;

use api\modules\seller\models\forms\ProductSkuModel;
use api\modules\seller\models\forms\ProductSkuQuery;
use fanyou\tools\ArrayHelper;
use fanyou\models\base\SearchModel;
use  yii\web\NotFoundHttpException;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-03-26
 */
class ProductSkuService extends BasicService
{

/*********************ProductSku模块服务层************************************/
	/**
	 * 添加一条 商品SKU
	 * @param ProductSkuModel $model
     * @return mixed
     * @throws \Throwable
     */
	public function addProductSku(ProductSkuModel $model)
	{
		$model->insert();
		return $model->getPrimaryKey();
	}
    /**
     * 批量添加 商品SKU
     * @param array $rows
     * @return int
     * @throws \yii\db\Exception
     */
    public function batchAddProductSku(array $rows)
    {
        // 批量写入数据
        $field = ['bar_code', 'product_id', 'origin_price', 'cost_price', 'member_price', 'sale_price',
            'sku_stock', 'shop_id', 'tag_ids','tag_detail_ids','tag_snap','image', 'created_at', 'updated_at'];
        !empty($rows) && parent::DbCommand()->batchInsert(ProductSkuModel::tableName(), $field, $rows)->execute();
        return count($rows);
    }

    /**
     * 分页获取列表
     * @param ProductSkuQuery $query
     * @return array
     * @throws NotFoundHttpException
     */
	public function getProductSkuPage(ProductSkuQuery $query): array
	{
		$searchModel = new SearchModel([
			'model' => ProductSkuModel::class,
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
    public function getProductSkuList(ProductSkuQuery $query): array
    {
        $searchModel = new SearchModel([
            'model' => ProductSkuModel::class,
            'scenario' => 'default',
            'partialMatchAttributes' => ['name'], // 模糊查询
            'defaultOrder' => [
                'created_at' => SORT_DESC
            ],
        ]);
        $searchWord = $searchModel->search(array_filter($query->toArray()));
        return ArrayHelper::toArray($searchWord->getModels());
    }
	/**
	 * 根据Id获取商品SKU
	 * @param $id
	 * @return Object
	 */
	public function getOneById($id)
	{
		return ProductSkuModel::findOne($id);
	}

	/**
	 * 根据Id更新商品SKU
	 * @param ProductSkuModel $model
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function updateProductSkuById (ProductSkuModel $model): int
	{
		$model->setOldAttribute('id',$model->id);
		return $model->update();
	}
	/**
	 * 根据Id删除商品SKU
	 * @param $id
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function deleteById ($id) : int
	{
		$model = ProductSkuModel::findOne($id);
		return  $model->delete();
	}
}
/**********************End Of ProductSku 服务层************************************/


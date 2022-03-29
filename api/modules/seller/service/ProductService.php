<?php

namespace api\modules\seller\service;

use api\modules\seller\models\forms\ProductModel;
use api\modules\seller\models\forms\ProductQuery;
use api\modules\seller\models\forms\ProductReplyModel;
use api\modules\seller\models\forms\ProductReplyQuery;
use api\modules\seller\models\forms\ProductSkuModel;
use api\modules\seller\models\forms\ProductSkuQuery;
use api\modules\seller\models\forms\ProductSkuResultModel;
use fanyou\enums\HttpErrorEnum;
use fanyou\enums\QueryEnum;
use fanyou\enums\SortEnum;
use fanyou\enums\StatusEnum;
use fanyou\error\errorCode\ErrorProduct;
use fanyou\error\FanYouHttpException;
use fanyou\models\base\SearchModel;
use fanyou\tools\ArrayHelper;
use yii\base\BaseObject;
use yii\web\NotFoundHttpException;

/**
 * Class ProductService
 * @package api\modules\seller\service
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-06-09 19:07
 */
class ProductService extends BasicService
{
    /*********************Product模块服务层************************************/
    /**
     * 添加一条EX_商品
     * @param ProductModel $model
     * @return mixed
     * @throws \Throwable
     */
    public function addProduct(ProductModel $model)
    {
        $skuList = $model->sku_list;
        $pSpecs = $model->product_spec;
        unset($model->sku_list);
        unset($model->product_spec);
        if ($model->is_sku && empty($skuList)) {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, ErrorProduct::ERROR_SKU_PARAMS);
        }
        if ($model->is_sku) {
            $priceSku = $skuList[0];
            $model->origin_price = $priceSku['origin_price'];
            $model->sale_price = $priceSku['sale_price'];
            $model->member_price = $priceSku['member_price'];
            $model->cost_price = $priceSku['cost_price'];
        }
        //销量重新计算
        $model->sales_number=$model->base_sales_number+$model->real_sales_number;
        $model->insert();
        $productId = $model->getPrimaryKey();
        if ($productId) {
            if (!empty($model->is_sku)) {
                $this->fillSkuAndResult($productId, $pSpecs['spec_list'], $skuList, StatusEnum::ENABLED, $model->cover_img);
            } else {
                $sku = new ProductSkuModel();
                $sku->id = $productId;

                $sku->product_id = $sku->id;
                $sku->bar_code = $model->bar_code;
                $sku->member_price = $model->member_price;
                $sku->sale_price = $model->sale_price;
                $sku->origin_price = $model->origin_price;
                $sku->cost_price = $model->cost_price;

                $sku->images = json_decode($model->images)[0];
                $sku->stock_number = $model->stock_number;
                $sku->is_sku = StatusEnum::DISABLED;
                $this->addProductSku($sku);

            }
        }
        return $productId;
    }

    /**
     * 局部更新
     * @param ProductModel $model
     * @return int
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function updatePartById(ProductModel $model): int
    {
        $old = $this->getOneById($model->id);
        if (!empty($model->name)) {
            if ($model->name !== $old->name) {
                $this->verifyRepeatName($model->name);
            }
        }
        $model->setOldAttribute('id', $model->id);
        $model->updated_at = time();
        return $model->update(false);
    }

    /**
     * 全量更新
     * @param ProductModel $model
     * @return int
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function updateProductById(ProductModel $model): int
    {
        $skuList = $model->sku_list;
        $pSpecs = $model->product_spec;
        unset($model->sku_list);
        unset($model->product_spec);
        $old = $this->getOneById($model->id);
        if (!empty($model->name)) {
            if ($model->name !== $old->name) {
                $this->verifyRepeatName($model->name);
            }
        }
        //销量重新计算
        $model->sales_number=$model->base_sales_number+ $old->real_sales_number;

        $model->setOldAttribute('id', $model->id);
        $model->updated_at = time();
        if (empty($model->share_img)) {
            ProductModel::updateAll(['share_img' => null], ['id' => $model->id]);
        }
        $result = $model->update(false);
        if ($result) {
            //如果是切换商品规格 为 多规格，或单规格
            if (!empty($pSpecs)) {
                $this->delProductSkuResultById($model->id);
            }
            $this->deleteProductSkuByProductId($model->id);
            $this->fillSkuAndResult($model->id, $pSpecs['spec_list'], $skuList, $model->is_sku, json_decode($model->images)[0]);

        }
        return $result;
    }


    /**
     * 分页获取列表
     * @param ProductQuery $query
     * @return array
     * @throws \yii\web\NotFoundHttpException
     */
    public function getProductPage(ProductQuery $query): array
    {
        unset($query['category_id']);
        $searchModel = new SearchModel([
            'model' => ProductModel::class,
            'scenario' => 'default',
            'partialMatchAttributes' => ['name',], // 模糊查询  'category_id'
            'defaultOrder' => $query->productOrdersArray('show_order', SORT_ASC),
            'pageSize' => $query->limit,

        ]);
        $searchWord = $searchModel->search($query->toArray([], [QueryEnum::LIMIT]));
        $list = ArrayHelper::toArray($searchWord->getModels());
        if (count($list)) {
            foreach ($list as $key => $value) {
                $stockNumber = 0;
                $skuList = $value['skuList'];
                if (!empty($skuList)) {
                    foreach ($skuList as $k => $v) {
                        $stockNumber += $v['stock_number'];
                    }
                }
                $list[$key]['stock_number'] = $stockNumber;
              if($value['real_sales_number'] && $value['store_count']){
                $list[$key]['conversion_rate'] = round(($value['real_sales_number']/$value['store_count'])*100);
              }else{
                $list[$key]['conversion_rate'] = 0;
              }
            }
        }
        $result['list'] = $list;
        $result['totalCount'] = $searchWord->pagination->totalCount;
        return $result;
    }

    public function getProductIds(ProductQuery $query): array
    {
        unset($query['category_id']);
        $searchModel = new SearchModel([
            'model' => ProductModel::class,
            'scenario' => 'default',
            'partialMatchAttributes' => ['name',], // 模糊查询  'category_id'
            'defaultOrder' => $query->getOrdersArray(SortEnum::SHOW_ORDER, SORT_ASC),
            'select' => ['id'],
            'distinct' => true,
        ]);

        $searchWord = $searchModel->search($query->toArray());
        return array_column($searchWord->getModels(), 'id');

    }


    /**
     * 取所有商品列表
     * @param ProductQuery $query
     * @return array
     * @throws NotFoundHttpException
     */
    public function getProductList(ProductQuery $query): array
    {
        $searchModel = new SearchModel([
            'model' => ProductModel::class,
            'scenario' => 'default',
            'partialMatchAttributes' => ['name',], // 模糊查询  'category_id'
            'defaultOrder' => $query->getOrdersArray(SortEnum::SHOW_ORDER, SORT_ASC),

        ]);

        $searchWord = $searchModel->search($query->toArray());

        return $searchWord->getModels();
    }

    /**
     * 根据Id获取商品
     * @param $id
     * @return ProductModel
     */
    public function getOneById($id): ?ProductModel
    {
        $productInfo = ProductModel::findOne($id);
        return $productInfo;
    }

    /**
     * 查询sku联合查询
     * @param $id
     * @return array
     * @throws FanYouHttpException
     */
    public function getOneWithSkuByProductId($id): array
    {
        $stockNumber = 0;
        $union = 'skuDetail';

        $array = ProductModel::find()->andWhere(['rf_product.id' => $id])->joinWith($union)->one();
        if (empty($array)) {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, ErrorProduct::UN_EXISTS);
        }
        $skuList = $array[$union];
        if (!empty($skuList)) {
            foreach ($skuList as $k => $v) {
                $stockNumber += $v['stock_number'];
            }
        }
        $array['stock_number'] = $stockNumber;

        //评论列表
      $query = new ProductReplyQuery();
      $data['product_id'] = $array['id'];
      $query->setAttributes($data);
      $list = [];
      if ( $query->validate()) {
        $service = new ProductReplyService();
        $list = $service->getProductReplyPage( $query);
      }

      $da =  ArrayHelper::toArray($array);

      $da['productReply'] = $list;
      return $da;
    }

    /**
     * 根据Id删除商品
     * @param $id
     * @return int
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function deleteProductById($id): int
    {
        $model = ProductModel::findOne($id);
        if (!empty($model)) {
            $result = $model->delete();
            if ($result) {
                $this->delProductSkuResultById($id);
                $this->deleteProductSkuByProductId($id);
            }
            return $result;
        }
        return 0;
    }

    /**
     * 根据分类id获取所有商品Id
     * @param $categoryId
     * @return array
     */
    public function getProductIdByCategoryId($categoryId): array
    {
        $array = ProductModel::find()->select(['id'])->where(['category_id' => $categoryId])->asArray()->all();
        return array_column($array, "id");
    }

    /**
     * 根据Id更新商品SKU
     * @param ProductSkuModel $model
     * @return int
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function updateProductSkuById(ProductSkuModel $model): int
    {
        $model->setOldAttribute('id', $model->id);
        return $model->update();
    }

    /**
     * 根据Id删除商品SKU
     * @param $id
     * @return int
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function deleteProductSkuById($id): int
    {
        $result = 0;
        $model = ProductSkuModel::findOne($id);
        if (!empty($model)) {
            $result = $model->delete();
        }
        return $result;
    }

    /**
     * 根据商品Id删除所有SKU
     * @param $productId
     * @return int
     */
    public function deleteProductSkuByProductId($productId): int
    {
        return ProductSkuModel::deleteAll(['product_id' => $productId]);
    }

    /**
     * 分页获取商品SKU列表
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
        $searchWord = $searchModel->search($query->toArray([], ['limit']));
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
        $searchWord = $searchModel->search($query->toArray());
        return ArrayHelper::toArray($searchWord->getModels());
    }

    public function addProductSku(ProductSkuModel $sku)
    {
        $sku->insert();
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
        $field = ['id', 'bar_code', 'images', 'product_id', 'origin_price', 'cost_price', 'member_price', 'sale_price',
            'stock_number', 'spec_snap', 'created_at', 'updated_at'];
        sort($field);
        !empty($rows) && parent::DbCommand()->batchInsert(ProductSkuModel::tableName(), $field, $rows)->execute();
        return count($rows);
    }


    /**
     * 添加一条商品SKU展示
     * @param $productId
     * @param $tagSnap
     * @param $tagDetailSnap
     * @return mixed
     * @throws \Throwable
     */
    public function addProductSkuResult($productId, $tagSnap)
    {
        if (empty($tagSnap)) {
            return;
        }
        $model = new ProductSkuResultModel();
        $model->id = $productId;
        $model->tag_snap = json_encode($tagSnap, JSON_UNESCAPED_UNICODE);
        $model->insert();

        return $model->getPrimaryKey();
    }

    /**
     * 删除一条展示记录
     * @param $productId
     * @return false|int
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function delProductSkuResultById($productId)
    {
        $result = 0;
        $model = ProductSkuResultModel::findOne($productId);
        if (!empty($model)) {
            $result = $model->delete();
        }
        return $result;
    }

    public function verifyRepeatName($productName,$force=true): int
    {
        $count = ProductModel::find()->select(['id'])->where(['name' => $productName])->count();
        if ($count && $force) {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, ErrorProduct::NAME_REPEAT);
        }
        return $count;
    }


    /**
     * 批量添加商品sku时转义sku
     * @param $productId
     * @param $specList
     * @param $skuList
     * @param int $isSku
     * @param $coverImg
     * @throws \Throwable
     * @throws \yii\db\Exception
     */
    public function fillSkuAndResult($productId, $specList, $skuList, $isSku = 1, $coverImg = null)
    {
        $now = time();
        $tagDetailSnap = [];

        foreach ($skuList as $k => $v) {

            $tagDetailSnap[] = $v;
            if ($isSku) {
                $skuV['id'] = parent::getRandomId();
            } else {
                $skuV['id'] = $productId;
            }
            $skuV['product_id'] = $productId;
            if (!empty($v['images'])) {
                $skuV['images'] = $v['images'];
            } else {
                $skuV['images'] = $coverImg;
            }
            $skuV['bar_code'] = $v['bar_code'];
            $skuV['stock_number'] = $v['stock_number'];
            $skuV['member_price'] = $v['member_price'];
            $skuV['cost_price'] = $v['cost_price'];
            $skuV['sale_price'] = $v['sale_price'];
            $skuV['origin_price'] = $v['origin_price'];
            if(!empty($v['spec_snap'])){
                $skuV['spec_snap'] = json_encode($v['spec_snap'], JSON_UNESCAPED_UNICODE);
            }else{
                $skuV['spec_snap'] =null;
            }

            $skuV['created_at'] = $now;
            $skuV['updated_at'] = $now;
            ksort($skuV);
            $skuList[$k] = $skuV;
        }

        $this->batchAddProductSku($skuList);

        $this->addProductSkuResult($productId, $specList);
    }
}
/**********************End Of Product 服务层************************************/


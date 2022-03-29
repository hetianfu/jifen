<?php

namespace api\modules\mobile\service;

use api\modules\mobile\models\forms\PinkConfigModel;
use api\modules\mobile\models\forms\PinkModel;
use api\modules\mobile\models\forms\PinkPartakeQuery;
use api\modules\mobile\models\forms\ProductModel;
use api\modules\mobile\models\forms\ProductQuery;
use api\modules\mobile\models\forms\ProductReplyModel;
use api\modules\mobile\models\forms\ProductSkuModel;
use api\modules\mobile\models\forms\ProductSkuQuery;
use api\modules\mobile\models\forms\ProductSkuResultModel;
use api\modules\mobile\models\forms\SaleProductModel;
use api\modules\mobile\models\forms\UserProductQuery;
use api\modules\mobile\models\ProductResult;
use api\modules\seller\models\forms\SaleProductLimitModel;
use EasyWeChat\Kernel\Support\Arr;
use fanyou\enums\entity\StrategyTypeEnum;
use fanyou\enums\HttpErrorEnum;
use fanyou\enums\NumberEnum;
use fanyou\enums\QueryEnum;
use fanyou\enums\SortEnum;
use fanyou\enums\StatusEnum;
use fanyou\error\errorCode\ErrorProduct;
use fanyou\error\FanYouHttpException;
use fanyou\models\base\SearchModel;
use fanyou\tools\ArrayHelper;
use fanyou\tools\StringHelper;
use Yii;
use yii\db\Expression;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-03-23
 */
class ProductService
{
  private $saleProductService;
  private $userProductService;
  private $pinkService;

  public function __construct()
  {
    $this->pinkService = new PinkService();
    $this->saleProductService = new SaleProductStrategyService();
    $this->userProductService = new UserProductService();
  }



  /*********************Product模块服务层************************************/

  /**
   * 分页获取列表
   * @param ProductQuery $query
   * @return array
   * @throws \yii\web\NotFoundHttpException
   */
  public function getProductPage(ProductQuery $query): array
  {
    $type = $query->type;
    //1推荐优先  2分类优先  3疯抢排行  4精品推荐  5全网热榜(搜索)

    switch ($type)
    {
      case 1:
        $defaultOrder['recommended'] = SORT_DESC;
        break;
      case 2:
        $defaultOrder['classification'] = SORT_DESC;
        break;
      case 3:
        $defaultOrder['rushed'] = SORT_DESC;
        break;
      case 4:
        $defaultOrder['high_quality'] = SORT_DESC;
        break;
      case 5:
        $defaultOrder['hot_search'] = SORT_DESC;
        break;
      default:
        $defaultOrder[SortEnum::SHOW_ORDER] = SORT_ASC;
    }



    $searchModel = new SearchModel([
      'model' => new  ProductModel(),
      'scenario' => 'default',
      'partialMatchAttributes' => ['name'], // 模糊查询
      'defaultOrder' => $defaultOrder,
      'pageSize' => $query->limit,
      'select' => ['id', 'category_id', 'merchant_id',
        'name', 'sub_title', 'short_title', 'type',
        'bar_code', 'unit_id', 'unit_snap', 'video_cover_img', 'video', 'share_img',
        'cover_img', 'search_code', 'tips', 'sale_strategy', 'is_distribute', 'support_coupon', 'support_score', 'is_freight',
        'cost_price', 'origin_price', 'sale_price', 'member_price', 'base_sales_number', 'real_sales_number', 'sales_number' => '(real_sales_number+base_sales_number)', 'is_hot', 'is_new', 'is_on_sale', 'is_sku', 'warn_line',
        'images', 'product_score','thumb_count', 'store_count', 'need_score']

    ]);
    $searchWord = $searchModel->search($query->toArray([], [QueryEnum::LIMIT]));
    $list = ArrayHelper::toArray($searchWord->getModels());

    if (count($list)) {
      foreach ($list as $k => $v) {
        $skuStock = ProductSkuModel::find()->select(['stock_number' => 'SUM(stock_number)'])->where(['product_id' => $v['id']])->one();
        $list[$k]['stockNumber'] = is_null($skuStock) ? 0 : $skuStock['stock_number'];
        $ProductReplyModel = ProductReplyModel::find()->where(['product_id'=>$v['id']])->one();
        $list[$k]['reply'] = $ProductReplyModel ? $ProductReplyModel->comment : '';
        $list[$k]['reply_portrait'] = $ProductReplyModel ? $ProductReplyModel->portrait : '';
      }
    }
//        if (count($list)) {
//            foreach ($list as $key => $value) {
//                $stockNumber = 0;
//                $skuList = $value['skuList'];
//                if (!empty($skuList)) {
//                    foreach ($skuList as $k => $v) {
//                        $stockNumber += $v['stock_number'];
//                    }
//                }
//                $list[$key]['stock_number'] = $stockNumber;
//            }
//        }

    $result['list'] = $list;
    $result['totalCount'] = $searchWord->pagination->totalCount;

    return $result;
  }

  public function count(ProductQuery $query)
  {
    $searchModel = new SearchModel([
      'model' => new  ProductModel(),
      'scenario' => 'default',
      'partialMatchAttributes' => ['name'], // 模糊查询
    ]);
    $searchWord = $searchModel->search($query->toArray());
    return (int)$searchWord->query->count();
  }


  /**
   * 商品点赞
   * @param ProductModel $model
   * @return int
   */
  public function thumbProduct(ProductModel $model)
  {
    return ProductModel::updateAll(['thumb_count' => new Expression('`thumb_count` + 1')], ['id' => $model->id]);
  }

  /**
   * 取消点赞
   * @param ProductModel $model
   * @return int
   */
  public function cancelThumbProduct(ProductModel $model)
  {
    return ProductModel::updateAll(['thumb_count' => new Expression('`thumb_count` - 1')], ['id' => $model->id]);
  }

  /**
   * 商品收藏
   * @param ProductModel $model
   * @return int
   */
  public function storeProduct(ProductModel $model)
  {
    return ProductModel::updateAll(['store_count' => new Expression('`store_count` + 1')], ['id' => $model->id]);
  }

  /**
   * 取消收藏
   * @param ProductModel $model
   * @return int
   */
  public function cancelStoreProduct(ProductModel $model)
  {
    return ProductModel::updateAll(['store_count' => new Expression('`store_count` - 1')], ['id' => $model->id]);
  }

  /**
   * 根据Id获取商品
   * @param $id
   * @return ProductModel
   */
  public function getOneById($id): ?ProductModel
  {
    return ProductModel::findOne(['id' => $id, 'is_on_sale' => StatusEnum::ENABLED]);
  }

  public function getOneWithSkuById($id, $pinkId = ''): ?ProductResult
  {
    $result = new ProductResult();
    $p = $this->getOneById($id);//$this->getOneById($id);
    $result->sharedAmount = $this->getSharedAmountByProduct($p->distribute_config);
    if (empty($p)) {
      throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, $p->name . ErrorProduct::NOT_SALE);
    }
    $result->setAttributes(StringHelper::toCamelize($p), false);

    //如果有策略
    if ($p->sale_strategy == StrategyTypeEnum::SECKILL) {
      //  $strategyStatus=0;
      //查看秒杀状态
      $saleProduct = $this->saleProductService->getSaleProductById($p->id);
      if (!empty($saleProduct)) {
        $saleProduct = $saleProduct->toArray();
        $effectArray = $this->saleProductService->getTodayEffectTimeById($saleProduct);
        $result->strategy = array_merge($saleProduct, $effectArray);
      }
    }

    if ($p->sale_strategy == StrategyTypeEnum::PINK) {
      //查看拼团状态
      $result->pinkConfig = PinkConfigModel::findOne(['product_id' => $id]);
      $arr = $this->getMyTake($id, $pinkId);
      $result->pinkConfig['hasPart'] = $arr['hasPart'];
      $result->pinkConfig['isSamePink'] = $arr['isSamePink'];
      if (!empty($pinkId) || !empty($arr['pinkId'])) {
        $result->pinkInfo = $this->getPinkInfoById(empty($pinkId) ? $arr['pinkId'] : $pinkId);
      }
    }

    if (!empty($p->is_sku)) {

      //没有策略，则返回策略skuList
      $array = $this->getSkuByProductId($p->id);

      $result->skuDetail = StringHelper::toCamelize($array['skuDetail']);
      $result->skuList =  $result->skuDetail;
      $result->stockNumber = $this->getProductStockNumber($result->skuDetail);
      $result->tagSnap = json_decode($array['tag_snap'],true);

      foreach ($result->tagSnap as &$row){

        $row['value_arr'] = explode(',',$row['value']);
      }

      //$result->sale_price = $result->skuDetail[0]['sale_price'];
    } else {
      $sku = $this->getSkuById($p->id);

      if (empty($sku)) {
        throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, $p->name . ErrorProduct::NO_SKU);
      }
      $result->originPrice = $sku['origin_price'];
      $result->salePrice = $sku['sale_price'];
      $result->memberPrice = $sku['member_price'];
      $result->stockNumber = $sku['stock_number'];
    }

    $result->salesReport = $this->getSalesReport($id);

    return $result;
  }

  /**
   * 获取商品分润金额
   * @param $distributeConfig
   * @return array|int|mixed|object|string
   */
  private function getSharedAmountByProduct($distributeConfig)
  {
    if (empty($distributeConfig)) {
      return [0, 0];
    }
    $identify = Yii::$app->user->identity['identify'];
    $arr = ArrayHelper::toArray(json_decode($distributeConfig));
    if (!isset($identify) || $identify >= count($arr)) {
      return [0, 0];
    }
    return $arr[$identify];
  }

  /**
   * 获取商品SKU
   * @param $pid
   * @return array
   */
  public function getSkuByProductId($pid): ?array
  {
    $sku = ProductSkuResultModel::find()->select(['rf_product_sku_result.id', 'rf_product_sku_result.tag_snap'])
      ->andWhere(['rf_product_sku_result.id' => $pid])
      //->orderBy('rf_product_sku_result.id')
      ->joinWith('skuDetail')
      ->one();
    return empty($sku) ? [] : $sku->toArray();

  }

  /**
   * 获取商品SKU
   * @param  $pid
   * @return array
   * @throws \yii\web\NotFoundHttpException
   */
  public function getProductSkuList($pid): array
  {
    $query = new ProductSkuQuery();
    $query->productId = $pid;
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


  /**
   * 根据Id获取商品SKU
   * @param $id
   * @return Object
   */
  public function getSkuById($id): ?ProductSkuModel
  {
    return ProductSkuModel::findOne((string)$id);
  }

  /**
   * 检测库存
   * @param $productList
   * @param int $now
   * @return mixed|string
   * @throws FanYouHttpException
   * @throws \Throwable
   */
  public function checkStock($productList)
  {
    $userId = Yii::$app->user->identity['id'];
    $result = StrategyTypeEnum::NORMAL;
    $stockList = $productList;
    foreach ($stockList as $k => $value) {
      if (!empty($value['saleStrategy'])) {
        $result = $value['saleStrategy'];
        $productId = $value['id'];
        if ($result == StrategyTypeEnum::SECKILL) {
          //检测限购策略
          $saleProduct = SaleProductModel::findOne($productId);
          if (!empty($saleProduct) && ($saleProduct->on_show == NumberEnum::ONE)) {
            $limit = SaleProductLimitModel::findOne(['key_id' => $productId . $saleProduct->start_date, 'user_id' => $userId, 'product_id' => $productId]);
            if (!empty($limit)) {
              throw new  FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, ErrorProduct::STRATEGY_LIMIT_NUN);
            }
          }
        }
      }
      $stockModel = $this->getSkuById($value['stockId']);
      if ($value['number'] > $stockModel->stock_number) {
        throw new  FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, ErrorProduct::STOCK_NUN_ENOUGH);
      }
    }
    return $result;
  }


  /**
   * 修改商品SKU
   * @param ProductSkuModel $model
   * @return false|int
   * @throws \Throwable
   * @throws \yii\db\StaleObjectException
   */
  public function updateSkuById(ProductSkuModel $model)
  {
    $model->setOldAttribute("id", $model->id);
    return $model->update();
  }

//    public function minusSkuNumberById($id, $number)
//    {
//        return ProductSkuModel::updateAll(['stock_number' => new Expression('`stock_number` - ' . $number)], ['and', ['id' => $id], ['>', 'stock_number', 0]]);
//    }
//
//    public function plusSkuNumberById($id, $number)
//    {
//        return ProductSkuModel::updateAll(['stock_number' => new Expression('`stock_number` + ' . $number)], ['and', ['id' => $id]]);
//    }

  /**
   * 获取商品的库存总量
   * @param $array
   * @return int
   */
  private function getProductStockNumber($array): int
  {
    $stockNumber = NumberEnum::ZERO;
    if (!empty($array)) {
      foreach ($array as $k => $v) {
        $thisStockNumber=empty( $v['stock_number'])?$v['stockNumber']:$v['stock_number'];

        $stockNumber += $thisStockNumber;
      }
    }
    return $stockNumber;
  }

  /**
   * 获取销售记录
   * @param $pid
   * @return array
   * @throws \yii\web\NotFoundHttpException
   */
  private function getSalesReport($pid): ?array
  {
    $query = new UserProductQuery();
    // $query->user_id=\Yii::$app->user->identity['id'];
    $query->product_id = $pid;
    $query->limit = NumberEnum::FIVE;

    return $this->userProductService->getUserProductWithUserInfoLimit($query);
  }

  /**
   * 获取我参与的
   * @param $productId
   * @param string $pinkId
   * @return null
   * @throws \yii\web\NotFoundHttpException
   */
  public function getMyTake($productId, $pinkId = '')
  {
    $arr = [];
    $userId = Yii::$app->user->identity['id'];
    //取当前生效的所有拼团列表
    $pinkList = $this->pinkService->getPinkListByProductId($productId);
    if (count($pinkList)) {
      //取当前进行中的拼团Id
      $pinkIds = implode(',', array_column($pinkList, 'id'));
      $query = new PinkPartakeQuery();
      $query->user_id = $userId;
      $query->pink_id = QueryEnum::IN . $pinkIds;
      $query->status = NumberEnum::ONE;
      //获取当前用户已经参与的的拼团记录
      $userTakePart = $this->pinkService->getOnePartake($query);
      if (!empty($userTakePart)) {
        $arr['pinkId'] = $userTakePart['pink_id'];
        if (!empty($pinkId) && ($pinkId != $arr['pinkId'])) {
          $arr['isSamePink'] = 1;
        } else {
          $arr['isSamePink'] = 0;
        }
        if (!empty($userId)) {
          $arr['hasPart'] = 1;
        }
      }
    }
    return $arr;
  }

  /**
   * 获取拼团信息
   * @param $productId
   * @param string $pinkId
   * @return Object|null
   * @throws \yii\web\NotFoundHttpException
   */
  public function getPinkInfoById($pinkId = '')
  {
    $pink = $this->pinkService->getOneById($pinkId);
    if (!empty($pink)) {
      $queryPart = new PinkPartakeQuery();
      $queryPart->pink_id = $pink['id'];
      $queryPart->status = NumberEnum::ONE;
      $pink['partakeList'] = $this->pinkService->getPartakeList($queryPart);

      return $pink;
    }
    return null;
  }
}
/**********************End Of Product 服务层************************************/


<?php

namespace api\modules\mobile\service;

use api\modules\mobile\models\forms\UserShopCartModel;
use api\modules\mobile\models\forms\UserShopCartQuery;
use fanyou\models\base\SearchModel;
use fanyou\enums\HttpErrorEnum;
use fanyou\enums\QueryEnum;
use fanyou\error\errorCode\ErrorProduct;
use fanyou\error\FanYouHttpException;
use fanyou\tools\ArrayHelper;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-04-27
 */
class UserShopCartService extends BasicService
{
    private $productService;

    public function __construct()
    {
        $this->productService = new ProductService();

    }

    /*********************UserShopCart模块服务层************************************/
    /**
     * 添加一条用户购物车
     * @param UserShopCartModel $model
     * @return mixed
     * @throws \Throwable
     */
    public function addUserShopCart(UserShopCartModel $model)
    {
        if (!$this->verifyProduct($model->product_id, $model->sku_id, $model->user_id)) {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, ErrorProduct::SYSTEM_ERROR);
        }
        $model->insert();
        return $model->getPrimaryKey();
    }

    /**
     * 分页获取列表
     * @param UserShopCartQuery $query
     * @return array
     * @throws \yii\web\NotFoundHttpException
     */
    public function getUserShopCartPage(UserShopCartQuery $query): array
    {
        $searchModel = new SearchModel([
            'model' => UserShopCartModel::class,
            'scenario' => 'default',
            'partialMatchAttributes' => ['name'], // 模糊查询
            'defaultOrder' => [
                'created_at' => SORT_DESC
            ],
            'pageSize' => $query->limit,
          //  'relations' => ['productInfo', 'skuInfo'],
        ]);

        $searchWord = $searchModel->search($query->toArray([], [QueryEnum::LIMIT]));

        $result['list'] = ArrayHelper::toArray($searchWord->getModels());

        $result['totalCount'] = $searchWord->pagination->totalCount;

        return $result;
    }

    /**
     * 统计userId购物车的数量
     * @param $userId
     * @return int
     */
    public function getUserShopCartCount($userId): int
    {
        return UserShopCartModel::find()->where(['user_id' => $userId])->count();
    }

    public function getUserShopCartList(UserShopCartQuery $query): array
    {
        $searchModel = new SearchModel([
            'model' => UserShopCartModel::class,
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
     * 联合查询 获取用户购物车
     * @param UserShopCartQuery $query
     * @return array
     * @throws \yii\web\NotFoundHttpException
     */
//    public function getUserShopCartWithProductList(UserShopCartQuery $query): array
//    {
//        $searchModel = new SearchModel([
//            'model' => UserShopCartModel::class,
//            'scenario' => 'default',
//            'partialMatchAttributes' => ['name'], // 模糊查询
//            'defaultOrder' => [
//                'created_at' => SORT_DESC
//            ],
//            'relations'=>['productInfo'],
//        ]);
//        $searchWord = $searchModel->search($query->toArray() );
//        return ArrayHelper::toArray($searchWord->getModels());
//    }

    /**
     * 根据Id获取物车
     * @param $id
     * @return Object
     */
    public function getOneById($id):?UserShopCartModel
    {
        return UserShopCartModel::findOne($id);
    }

    /**
     * 根据Id更新物车
     * @param UserShopCartModel $model
     * @return int
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function updateUserShopCartById(UserShopCartModel $model): int
    {
        $old=$this->getOneById($model->id);
        if (!$this->verifyProduct($old->product_id, $model->sku_id, $old->user_id)) {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, ErrorProduct::SYSTEM_ERROR);
        }
        $model->setOldAttribute('id', $model->id);
        return $model->update();
    }

    public function updateUserShopCartNumber(UserShopCartModel $model): int
    {
        $old=$this->getOneById($model->id);
        if (!$this->verifyProduct($old->product_id, $old->sku_id, null)) {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, ErrorProduct::SYSTEM_ERROR);
        }
        $model->setOldAttribute('id', $model->id);
        return $model->update();
    }

    /**
     * 根据Id删除物车
     * @param $id
     * @return int
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function deleteById($id): int
    {
        $model = UserShopCartModel::findOne($id);
        return $model->delete();
    }

    /**
     * 批量删除
     * @param $ids
     * @return int
     */
    public function deleteByIds(array $ids): int
    {
        $number = sizeof($ids);
        if ($number) {
            $number=  UserShopCartModel::deleteAll([  'in','id', $ids  ] );
        }
        return $number;
    }


    /**
     * 判定商品库存是否可以加入购物车
     * @param $productId
     * @param $skuId
     * @param $userId
     * @return bool
     * @throws FanYouHttpException
     */
    public function verifyProduct($productId, $skuId, $userId): bool
    {
        $product = $this->productService->getOneById($productId);
        if (empty($product) || $product->is_on_sale == 0) {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, ErrorProduct::NOT_SALE);
        }
        if (empty($product->is_sku)) {
            $skuId = $product->id;
        }
        $sku = $this->productService->getSkuById($skuId);
        if (empty($sku) || empty($sku->stock_number)) {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, ErrorProduct::STOCK_NUN_ENOUGH);
        }
        //加入之前，判定商品库存是否有
        if($userId){
        $cart = UserShopCartModel::findOne(['user_id' => $userId, 'product_id' => $productId, 'sku_id' => $skuId]);
        if (!empty($cart)) {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, ErrorProduct::PRODUCT_HAS_IN_CART);
        }
        }
        return true;
    }


}
/**********************End Of UserShopCart 服务层************************************/


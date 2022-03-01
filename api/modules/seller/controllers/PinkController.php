<?php

namespace api\modules\seller\controllers;

use api\modules\auth\ApiAuth;
use api\modules\seller\models\event\ProductEvent;
use api\modules\seller\models\forms\PinkConfigModel;
use api\modules\seller\models\forms\PinkConfigQuery;
use api\modules\seller\models\forms\PinkModel;
use api\modules\seller\models\forms\PinkPartakeQuery;
use api\modules\seller\models\forms\PinkQuery;
use api\modules\seller\models\forms\ProductModel;
use api\modules\seller\models\forms\ProductQuery;
use api\modules\seller\models\forms\ProductSkuQuery;
use api\modules\seller\service\PinkConfigService;
use api\modules\seller\service\PinkService;
use api\modules\seller\service\ProductService;
use api\modules\seller\service\ProductSkuResultService;
use fanyou\enums\entity\StrategyTypeEnum;
use fanyou\enums\HttpErrorEnum;
use fanyou\enums\NumberEnum;
use fanyou\enums\QueryEnum;
use fanyou\error\FanYouHttpException;
use fanyou\tools\ArrayHelper;
use Yii;
use yii\web\HttpException;

/**
 * Class PinkController
 * @package api\modules\seller\controllers
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-08-20
 */
class PinkController extends BaseController
{

    private $service;
    private $configService;
    private $productService;
    private $productSkuResultService;
    const EVENT_SAVE_PRODUCT = 'save-product';
    const EVENT_DEL_PRODUCT = 'del-product';

    public function init()
    {
        parent::init();
        $this->service = new PinkService();
        $this->configService = new PinkConfigService();
        $this->productService = new ProductService();
        $this->productSkuResultService = new ProductSkuResultService();

        $this->on(self::EVENT_SAVE_PRODUCT, ['api\modules\seller\service\event\ProductEventService', 'saveProduct']);

        $this->on(self::EVENT_DEL_PRODUCT, ['api\modules\seller\service\event\ProductEventService', 'deleteProduct']);
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => ApiAuth::class,
            'optional' => ['get-by-product-id', 'get-by-id', 'add', 'get-partake-list']
        ];
        return $behaviors;
    }
    /*********************Pink模块控制层************************************/

    /**
     *  添加拼团配置
     * @return mixed
     * @throws FanYouHttpException
     */
    public function actionAddConfig()
    {
        $model = new PinkConfigModel();
        $model->setAttributes($this->getRequestPost());
        if ($model->validate()) {
            $productId = $this->saveProduct($this->productService->getOneById(Yii::$app->request->post('productId')), Yii::$app->request->post('title'));
            // 添加一条促销商品
            $model->product_id = $productId;
            //$model->id = $productId;
            return $this->configService->addPinkConfig($model);
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, parent::getModelError($model));
        }
    }

    /**
     *  更新拼团配置
     * @return mixed
     * @throws FanYouHttpException
     */
    public function actionUpdateConfigById()
    {
        $model = new PinkConfigModel();
        $model->setAttributes($this->getRequestPost());
        if ($model->validate()) {
            return $this->configService->updatePinkConfigById($model);
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, parent::getModelError($model));
        }
    }

    /**
     * 分页获取拼团配置列表
     * @return mixed
     */
    public function actionGetConfigPage()
    {
        $query = new PinkConfigQuery();
        $query->setAttributes($this->getRequestGet());

        $productName = parent::getRequestId('productName');

        if (!empty($productName)) {
            $productQuery = new ProductQuery();
            $productQuery->name = $productName;

            $idList = $this->productService->getProductIds($productQuery);

            if (!count($idList)) {
                return parent::getEmptyPage();
            }
            $query->product_id = QueryEnum::IN . implode(",", $idList);
        }
        return $this->configService->getPinkConfigPage($query);
    }

    /**
     * 删除
     * @return mixed
     */
    public function actionDelConfigById()
    {
        $id = parent::getRequestId();
        $productId=$this->configService->getOneById($id)['product_id'];
        $resultD = $this->configService->deleteById($id);
        $result = $this->productService->deleteProductById($productId);
        if ($result) {
            $event = new ProductEvent();
            $event->productId = $productId;
            $this->trigger(self::EVENT_DEL_PRODUCT, $event);
        }
        return $resultD;
    }

    /**
     * 分页获取拼团列表
     * @return mixed
     * @throws HttpException
     */
    public function actionGetPinkPage()
    {
        $query = new PinkQuery();
        $query->setAttributes($this->getRequestGet());
        if ($query->validate()) {
            return $this->service->getPinkPage($query);
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, parent::getModelError($query));
        }
    }

    /**
     * 更新拼团信息
     * @return mixed
     * @throws FanYouHttpException
     */
    public function actionUpdatePinkById()
    {
        $model = new PinkModel();
        $model->setAttributes($this->getRequestPost());
        if ($model->validate()) {
            return $this->service->updatePinkById($model);
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, parent::getModelError($model));
        }
    }

    /**
     * 根据商品取拼团信息
     * @return mixed
     */
    public function actionGetByProductId()
    {

        $pink = $this->service->getByProductId(parent::getRequestId('productId'));

        return $pink;
    }

    /**
     * 取此团详情
     * @return mixed
     */
    public function actionGetById()
    {
        $pink = $this->service->getOneById(parent::getRequestId());
        if (!empty($pink)) {
            $query = new PinkPartakeQuery();
            $query->pink_id = parent::getRequestId();
            $query->status = NumberEnum::ONE;
            $pink['partakeList'] = $this->service->getList($query);
        }
        return $pink;
    }

    /**
     * 完结拼团
     * @return mixed
     */
    public function actionFinishPink()
    {
        $pinkId = Yii::$app->request->post('id');
        return $this->service->finishPink($pinkId);
    }

    /**
     * 取参与人列表
     * @return mixed
     * @throws FanYouHttpException
     */
    public function actionGetPartakeList()
    {
        $query = new PinkPartakeQuery();
        $query->setAttributes($this->getRequestGet());
        if ($query->validate()) {

            return $this->service->getPartakeList($query);
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, parent::getModelError($query));
        }
    }


    private function saveProduct($array, $name)
    {
        $model = new ProductModel();
        $model->setAttributes(ArrayHelper::toArray($array), false);
        $oldProductId = $model->id;
        unset($model->id);
        $model->sale_strategy = StrategyTypeEnum::PINK;

        $model->name = $name;
        if ($model->is_sku) {
            $query = new ProductSkuQuery();
            $query->product_id = $oldProductId;
            $model->sku_list = $this->productService->getProductSkuList($query);

            $product_spec = $this->productSkuResultService->getOneById($oldProductId);
            $skuResult['spec_list'] = json_decode($product_spec['tag_snap']);
            $skuResult['merchant_id'] = $model->merchant_id;

            $model->product_spec = $skuResult;
        }
        $model->category_list = $model->category_id;
        if ($model->validate()) {
            $event = new ProductEvent();
            $categoryList = $model->category_list;
            if (is_array($categoryList)) {
                $categoryIds = array_column($categoryList, 'id');
                $categoryNames = array_column($categoryList, 'name');
                $event->categoryIdList = $categoryIds;
                $model->category_id = json_encode($model->category_id);
                $model->category_snap = json_encode($categoryNames, JSON_UNESCAPED_UNICODE);
            }
            $this->productService->verifyRepeatName($model->merchant_id, $model->name);
            $model->share_img = null;

            $model->base_sales_number = 0;
            $model->real_sales_number = 0;
            $model->sales_number = 0;
            $productId = $this->productService->addProduct($model);
            $event->productId = $productId;
            $this->trigger(self::EVENT_SAVE_PRODUCT, $event);
            return $productId;
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Expectation_Failed, parent::getModelError($model));
        }
    }

}
/**********************End Of Pink 控制层************************************/ 



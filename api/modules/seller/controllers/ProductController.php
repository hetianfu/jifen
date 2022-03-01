<?php

namespace api\modules\seller\controllers;

use api\modules\auth\ApiAuth;
use api\modules\mobile\models\forms\ProductSkuResultModel;
use api\modules\seller\models\forms\ProductCategoryModel;
use api\modules\seller\models\forms\ProductReplyModel;
use api\modules\seller\models\forms\StoreClientModel;
use api\modules\seller\models\forms\SystemConfigTabModel;
use api\modules\seller\models\forms\SystemConfigTabValueModel;
use api\modules\seller\service\UserShareService;
use api\modules\seller\models\event\ProductEvent;
use api\modules\seller\models\forms\ProductCategoryQuery;
use api\modules\seller\models\forms\ProductModel;
use api\modules\seller\models\forms\ProductQuery;
use api\modules\seller\models\forms\ProductSkuModel;
use api\modules\seller\service\ProductCategoryService;
use api\modules\seller\service\ProductService;
use EasyWeChat\Kernel\Support\Arr;
use fanyou\components\SystemConfig;
use fanyou\enums\entity\StrategyTypeEnum;
use fanyou\enums\HttpErrorEnum;
use fanyou\enums\QueryEnum;
use fanyou\enums\SystemConfigEnum;
use fanyou\error\errorCode\ErrorProduct;
use fanyou\error\FanYouHttpException;
use fanyou\models\common\Attachment;
use fanyou\tools\ArrayHelper;
use fanyou\tools\ExcelHelper;
use fanyou\tools\StringHelper;
use fanyou\tools\UploadHelper;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Yii;
use yii\helpers\ReplaceArrayValue;

/**
 * Product
 * @author E-mail: Administrator@qq.com
 *
 */
class ProductController extends BaseController
{

    private $service;
    private $cpService;
    private $shareService;
    const EVENT_SAVE_PRODUCT = 'save-product';
    const EVENT_DEL_PRODUCT = 'del-product';

    public function init()
    {
        parent::init();
        $this->service = new ProductService();
        $this->cpService = new ProductCategoryService();
        $this->shareService = new UserShareService();
        $this->on(self::EVENT_SAVE_PRODUCT, ['api\modules\seller\service\event\ProductEventService', 'saveProduct']);
        $this->on(self::EVENT_DEL_PRODUCT, ['api\modules\seller\service\event\ProductEventService', 'deleteProduct']);

    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => ApiAuth::class,
            'optional' => ['export', 'gather-product','all-product-img-to-oss']
        ];
        return $behaviors;
    }
    /*********************Product模块控制层************************************/

    /**
     * 添加商品信息
     * @return mixed
     * @throws FanYouHttpException
     */
    public function actionAddProduct()
    {

        $model = new ProductModel();
        $request = $this->getRequestPost();
        $model->setAttributes($request, false);


        $old = ProductModel::findOne(['name' => $model->name]);
        if (!empty($old)) {
            throw new FanYouHttpException(HttpErrorEnum::Expectation_Failed, '商品名称重复');
        }

        //stockNumber不是表内字段
        $model->stock_number = $request['stock_number'];
        $model->category_list = $request['category_list'];
        $model->sku_list = $request['sku_list'];
        $model->product_spec = $request['product_spec'];

        //保存评论
        $productReply = $request['productReply'];
        $productId = $this->saveProduct($model);

      $productReply = json_decode($productReply,true);
        if($productReply){
           foreach ($productReply as $row){
              //user_name  portrait comment
            $product_rep = new ProductReplyModel([
              'user_name'=>$row['user_name'],
              'portrait'=>$row['portrait'],
              'comment'=>$row['comment'],
              'product_id'=>$productId,
            ]);
             $product_rep->save(false);

           }
        }


        return $productId;
    }

    private function saveProduct(ProductModel $model)
    {
        //清除缓存
        $tokenId ='QUERY-PRODUCT-ID-'.$model->category_id;
        Yii::$app->cache->delete($tokenId);

        if ($model->validate()) {
            $event = new ProductEvent();
            $categoryList = $model->category_list;
            if (is_array($categoryList)) {
                $categoryIds = array_column($categoryList, 'id');
                //清除缓存
                foreach ( $categoryIds as $k=>$v){
                    $tokenId ='QUERY-PRODUCT-ID-'.$v;
                    Yii::$app->cache->delete($tokenId);
                }

                $categoryNames = array_column($categoryList, 'name');
                $event->categoryIdList = $categoryIds;
                $model->category_id = json_encode($categoryIds);
                $model->category_snap = json_encode($categoryNames, JSON_UNESCAPED_UNICODE);
            }
            $this->service->verifyRepeatName($model->name, false);

            $productId = $this->service->addProduct($model);
            $event->productId = $productId;
            $this->trigger(self::EVENT_SAVE_PRODUCT, $event);
            return $productId;
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Expectation_Failed, parent::getModelError($model));
        }
    }

    /**
     * 分页获取商品列表
     * @return mixed
     * @throws FanYouHttpException
     */
    public function actionGetProductPage()
    {
      //推荐优先  分类优先  精品推荐  疯抢排行  全网热榜
        $query = new ProductQuery();
        $query->setAttributes($this->getRequestGet());
        if ($query->validate()) {
            //根据分类id取商品ids,
            if ($query->category_id) {
                $cpQuery = new ProductCategoryQuery();
                $cpQuery->category_id = QueryEnum::IN . implode(',', $query->category_id);
                $productIds = $this->cpService->getProductIds($cpQuery);

                if (empty($productIds)) {
                    return parent::getEmptyPage();
                }
                unset($query['category_id']);
                $query->id = QueryEnum::IN . $productIds;

            }
            if (empty($query->sale_strategy)) {
                $query->sale_strategy = QueryEnum::IN . StrategyTypeEnum::NORMAL . ',' . StrategyTypeEnum::SCORE;
            }
            return $this->service->getProductPage($query);
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, parent::getModelError($query));
        }
    }


    /**
     * 根据Id获取详情
     * @return mixed
     * @throws HttpException
     */
    public function actionGetProductById()
    {
        return $this->service->getOneWithSkuByProductId(parent::getRequestId());
    }

    /**
     * 局部根据Id更新商品详情
     * @return mixed
     * @throws FanYouHttpException
     */
    public function actionUpdateById()
    {
        $model = new ProductModel(['scenario' => 'update']);
        $model->setAttributes($this->getRequestPost(false));

        if ($model->validate()) {
            if (!$model->is_sku) {
                $skuList = $model->sku_list[0];
                $skuList['cost_price'] = $model->cost_price;
                $skuList['stock_number'] = parent::postRequestId('stockNumber');
                $skuList['sale_price'] = $model->sale_price;
                $skuList['member_price'] = $model->member_price;
                $skuList['origin_price'] = $model->origin_price;
                $model->sku_list = [$skuList];
            } else {
                $firstSku = $model->sku_list[0];
                $model->origin_price = $firstSku['origin_price'];
                $model->sale_price = $firstSku['sale_price'];
                $model->member_price = $firstSku['member_price'];
                $model->cost_price = $firstSku['cost_price'];
            }
            $event = new ProductEvent();
            $categoryList = $model->category_list;
            if (is_array($categoryList)) {
                $event->categoryIdList = array_column($categoryList, 'id');
                //清除缓存
                foreach ( $event->categoryIdList as $k=>$v){
                $tokenId ='QUERY-PRODUCT-ID-'.$v;
                Yii::$app->cache->delete($tokenId);
                }

                $model->category_id = json_encode($event->categoryIdList);
                $model->category_snap = json_encode(array_column($categoryList, 'name'), JSON_UNESCAPED_UNICODE);
            }
            $event->productId = $model->id;
            $result = $this->service->updateProductById($model);
            if ($result) {
                $this->trigger(self::EVENT_SAVE_PRODUCT, $event);
            }
            return $result;
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, parent::getModelError($model));
        }
    }

    /**
     * 单字段局部更新
     * @return mixed
     */
    public function actionUpdatePartById()
    {

        $model = new ProductModel(['scenario' => 'update']);
        $model->setAttributes($this->getRequestPost(false));


        $result = $this->service->updatePartById($model);

        return $result;

    }

    /**
     * 根据Id删除
     * @return mixed
     * @throws HttpException
     */
    public function actionDelProductById()
    {
        $id = parent::getRequestId();
        $result = $this->service->deleteProductById($id);
        if ($result) {
            $event = new ProductEvent();
            $event->productId = $id;
            $this->trigger(self::EVENT_DEL_PRODUCT, $event);
        }
        return $result;
    }

    /**
     * 根据Id更新商品SKU
     * @return mixed
     * @throws FanYouHttpException
     */
    public function actionUpdateProductSkuById()
    {

        $model = new ProductSkuModel(['scenario' => 'update']);
        $model->setAttributes($this->getRequestPost(false));
        if ($model->validate()) {
            return $this->service->updateProductSkuById($model);
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, parent::getModelError($model));
        }
    }

    /**
     * 根据Id删除
     * @return mixed
     * @throws HttpException
     */
    public function actionDelProductSkuById()
    {

        return $this->service->deleteProductSkuById(parent::getRequestId());
    }


    /**
     * 清空商品分享图
     * @return mixed
     */
    public function actionCleanShareImg()
    {

        $this->shareService->cleanShareImg(parent::getRequestId('userId'));
        $this->shareService->cleanShareImg(parent::getRequestId('productId'));
        return 1;
    }


    /**
     * 导出商品
     * @return bool
     * @throws FanYouHttpException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function actionExport()
    {
        $query = new ProductQuery();
        $query->setAttributes($this->getRequestGet());
        $dataList = $this->service->getProductList($query);
        if (empty($dataList)) {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, ErrorProduct::EMPTY_PRODUCT);
        }
        $key = 0;
        foreach ($dataList as $k => $data) {
            $skuList = ArrayHelper::toArray($data['skuDetail']);
            foreach ($skuList as $j => $sku) {

                $list[$key]['bar_code'] = $sku['bar_code'];
                $list[$key]['sale_price'] = $sku['sale_price'];
                $list[$key]['cost_price'] = $sku['cost_price'];
                $list[$key]['spec_snap'] = $sku['spec_snap'];

                $list[$key]['id'] = $data['id'];
                $list[$key]['name'] = $data['name'];
                $list[$key]['supply_name'] = $data['supply_name'];
                $list[$key]['short_title'] = $data['short_title'];
                $list[$key]['real_sales_number'] = $data['real_sales_number'];
                $list[$key]['store_count'] = $data['store_count'];
                $list[$key]['created_at'] = $data['created_at'];
                $key++;
            }
        }
        $header = [
            ['ID', 'id'],
            ['商品名称', 'name'],
            ['供应商', 'supply_name'],

            ['规格', 'spec_snap'],

            ['条形码', 'bar_code'],
            ['价格', 'sale_price'],
            ['成本价', 'cost_price'],

            ['商品销量', 'real_sales_number'],
            ['喜欢人数', 'store_count'],
            ['创建时间', 'created_at', 'date', 'Y-m-d H:i:s'],
            ['简介', 'short_title'],
        ];
        // 导出Excel
        return ExcelHelper::exportData($list, $header, '商品信息_' . time(), '商品导出');
    }

    function actionGatherProduct()
    {

        $json_string = file_get_contents('C:\Users\Administrator\Documents\WeChat Files\wxid_d44f9k3ike7z21\FileStorage\File\2021-07\response.json');

        // 用参数true把JSON字符串强制转成PHP数组
        $data = json_decode($json_string, true);
        print_r(count($data['data']));
        exit;

        $categoryMap = ["83" => ["家电"], "85" => ["服饰"], "86" => ["粉底"], "87" => ["酒水"], "97" => ["汽车"]];
        $tokenId = '222222222222222222222';
        $tokenContent = Yii::$app->cache->get($tokenId);
        if (empty($tokenContent)) {
            $url = 'https://kltn.api.shop.tickscloud.com/v1/product/temp';
            $client = new Client();
            try {
                $response = $client->get($url, [
                    RequestOptions::HEADERS => [
                        'x-weimiao-mini-app-id' => 'wx22343eaeb0a18270',
                        'x-weimiao-tenantid' => '79VbzD0mAQ0LqBJ8']
                ]);

                $body = $response->getBody()->getContents();
                $e = ArrayHelper::toArray(json_decode($body));
                if ($e['code'] != 200) {
                    throw new FanYouHttpException($e['msg']);
                }
            } catch (\Exception $e) {
                $r['list'] = [];
                $r['total'] = 0;
                return $r;
            }
            $list = $e['data'];
            $tokenContent = json_encode($list);
            Yii::$app->cache->set($tokenId, $tokenContent, 7200);

        } else {
            $list = json_decode($tokenContent);
        }
        foreach ($list as $k => $value) {

            $value = ArrayHelper::toArray($value);
            $categoryId = $value['categoryId'];
            $p = new ProductModel();
            $p->is_on_sale = 1;
            $p->type = 'REAL';
            $p->id = $value['id'];
            $p->name = $value['name'];
            $p->is_hot = $value['hot'];
            $p->sub_title = $value['subTitle'];

            $categoryName = $categoryMap[$categoryId];
            $p->category_id = json_encode([(string)$categoryId]);

            $p->category_snap = json_encode($categoryName, JSON_UNESCAPED_UNICODE);
            $p->sale_price = $value['price'] / 100;
            $p->origin_price = $value['marketingPrice'] / 100;
            $p->member_price = $value['grouponSinglePrice'] / 100;
            $p->cost_price = $value['price'] / 100;

            if (count($value['attributes'])) {
                $attrs = array_column($value['attributes'], 'value');
                $p->tips = json_encode($attrs);
            }

            $p->need_score = $value['useCredits'];
            $p->unit_snap = json_encode(['id' => 1, 'name' => $value['quantityUnit']]);
            $p->unit_id = "1";

            $p->cover_img = $value['mainImage'];
            $p->images = json_encode($value['album'], JSON_UNESCAPED_UNICODE);
            $description = json_decode($value['description']);
            $buffer = '';
            foreach ($description as $d) {
                $buffer = $buffer . "<p><img src=" . $d . "></p>";
            }
            $p->description = $buffer;
            $p->volume = $value['volume'];
            $p->base_sales_number = $value['baseCommentNum'];
            $p->stock_number = $value['stock'];
            $p->is_sku = 0;
            $skuList = [];
            $skus = $value['skus'];
            foreach ($skus as $m => $v) {
                $sku = new ProductSkuModel();
                $sku->id = $v['id'];
                $sku->sale_price = $v['price'] / 100;
                $sku->origin_price = $v['marketingPrice'] / 100;
                $sku->cost_price = $sku->sale_price;
                $sku->member_price = $sku->sale_price;
                $sku->stock_number = $v['stock'];
                $sku->images = $v['image'];
                $sku->stock_number = $v['stock'];
                $sku->bar_code = $v['skuCode'];
                $sku->spec_snap = implode(",", $v['specificationsArray']);
                $skuList[] = $sku;
            }
            $p->sku_list = $skuList;
            $this->saveProduct($p);

            $result = new ProductCategoryModel();
            $result->category_id = (string)$categoryId;
            $result->product_id = (string)$p->id;
            $result->insert();
        }

        return $e['data'];
    }

    /**
     * 所有商品转存到阿里云
     */
    function actionAllProductImgToOss()
    {
        $aliOssConfig = SystemConfigTabModel::find()->select(['id'])->where(['eng_title' => SystemConfigEnum::ALI_OSS])->asArray()->one();
        $buckName = SystemConfigTabValueModel::find()->select(['value'])->where(['config_tab_id' => $aliOssConfig['id'], 'menu_name' => 'bucket_name'])->one()['value'];
        $list=ProductModel::find()->select(['id'])->asArray()->all();
        if(count($list)){
            foreach ($list as $p){
            $this->actionSingleSaveToOss($p['id'],$buckName);
            }
        }
        return count($list);
    }
    /**
     * 商品转存到阿里云
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    function actionProductImgToOss()
    {
        $aliOssConfig = SystemConfigTabModel::find()->select(['id'])->where(['eng_title' => SystemConfigEnum::ALI_OSS])->asArray()->one();
        $buckName = SystemConfigTabValueModel::find()->select(['value'])->where(['config_tab_id' => $aliOssConfig['id'], 'menu_name' => 'bucket_name'])->one()['value'];
        $productId = parent::getRequestId();
      return  $this->actionSingleSaveToOss($productId,$buckName);
    }


    function actionSingleSaveToOss($productId, $buckName)
    {
        $productUpdate = new ProductModel();
        $productInfo = ProductModel::findOne($productId);
        $productUpdate->cover_img = self::actionSaveToOss($productInfo->cover_img, $buckName);
        $productUpdate->video_cover_img = self::actionSaveToOss($productInfo->video_cover_img, $buckName);

        $images = json_decode($productInfo->images);
        if (count($images)) {
            foreach ($images as $img) {
                $imgarr[] = self::actionSaveToOss($img, $buckName);
            }
            $productUpdate->images = json_encode($imgarr);
        }
        $productUpdate->setOldAttribute("id", $productInfo->id);
        $productUpdate->update();
        self::actionProductSkuImgToOss($productId, $buckName);
        $description = $productInfo->description;
        preg_match_all("/[src|SRC]=[\'|\"](.*?)[\'|\"]>/", $description, $match);
        $desList = $match[0];
        if (count($desList)) {
            $desResult = "";
            foreach ($desList as $des) {
                $des= str_replace("c='","",$des);
                $des= str_replace("c=\"","",$des);
                $des= str_replace("'>","",$des);
                $des= str_replace("\"'>","",$des);
                if (strpos($des, "//") === 0) {
                    $des="http:".$des;
                }

                if (strpos($des, "http") === 0) {
                    $desResult = $desResult . "<p><img src='" . self::actionSaveToOss($des, $buckName) . "'/></p>";
                }
            }
            return   ProductModel::updateAll(['description'=>$desResult],['id'=>$productId]);
        }
        return count($desList);
    }

    function actionProductSkuImgToOss($productId, $buckName)
    {
        $skuList = ProductSkuModel::findAll(['product_id' => $productId]);
        foreach ($skuList as $sku) {
            $sku->images = self::actionSaveToOss($sku->images, $buckName);
            //更新SKU
            ProductSkuModel::updateAll(['images' => $sku->images], ['id' => $sku->id]);
        }
    }

    static function actionSaveToOss($img, $ossPrex)
    {   if(empty($img)){
        return '';
        }

        if (strstr($img, $ossPrex)) {
            return $img;
        }
        $upload = new UploadHelper(Yii::$app->request->post(), Attachment::UPLOAD_TYPE_IMAGES);
        $array = $upload->uploadFormUrl($img, StringHelper::random(32));


        $storeClient = new StoreClientModel();
        $storeClient->merchant_id = Yii::$app->user->identity['merchantId'];
        $storeClient->key = $array['key'];
        $storeClient->category_id =0;
        $arr = explode("/", $array['key']);
        $storeClient->name = substr($arr[count($arr) - 1], 0, -4);
        $storeClient->url = $array['accessUrl'];
        $storeClient->size = $array['size'];
        $storeClient->type = 'oss';
        $storeClient->file_type = 'images';
        $storeClient->insert();


        return $array['accessUrl'];
    }


}
/**********************End Of Product 控制层************************************/ 



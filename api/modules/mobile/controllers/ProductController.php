<?php

namespace api\modules\mobile\controllers;

use api\modules\auth\ApiAuth;
use api\modules\mobile\models\forms\CategoryModel;
use api\modules\mobile\models\forms\ProductCategoryQuery;
use api\modules\mobile\models\forms\ProductModel;
use api\modules\mobile\models\forms\ProductQuery;
use api\modules\mobile\models\forms\UserShareModel;
use api\modules\mobile\service\ProductCategoryService;
use api\modules\mobile\service\ProductService;
use api\modules\mobile\service\UserShareService;
use api\modules\mobile\service\WechatService;
use fanyou\common\ShareProduct;
use fanyou\components\PhotoMerge;
use fanyou\components\SystemConfig;
use fanyou\enums\entity\BasicConfigEnum;
use fanyou\enums\HttpErrorEnum;
use fanyou\enums\QueryEnum;
use fanyou\enums\StatusEnum;
use fanyou\enums\SystemConfigEnum;
use fanyou\error\errorCode\ErrorProduct;
use fanyou\error\FanYouHttpException;
use fanyou\models\common\Attachment;
use fanyou\tools\UploadHelper;
use Yii;
use yii\db\Expression;
use yii\web\HttpException;

/**
 * Class ProductController
 * @package api\modules\mobile\controllers
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-06-02 9:19
 */
class ProductController extends BaseController
{

    private $service;
    private $miniAppService;
    private $shareService;
    private $cpService;
    private $configService;

    public function init()
    {
        parent::init();
        $this->service = new ProductService();
        $this->miniAppService = new WechatService();
        $this->shareService = new UserShareService();
        $this->cpService = new ProductCategoryService();
        $this->configService = new SystemConfig();
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => ApiAuth::class,
            'optional' => ['create-share-img', 'get-by-id', 'get-page', 'get-count','get-sku-by-id']
        ];
        return $behaviors;
    }

    /*********************Product模块控制层************************************/
    protected function actionGetNumberFont()
    {
        $id = Yii::getAlias('@alifont') . '/number.ttf';
        return $id;
    }

    protected function actionGetAliFont()
    {
        $id = Yii::getAlias('@alifont') . '/Alibaba-PuHuiTi-Medium.ttf';
        return $id;
    }

    /**
     * 点赞商品
     * @return mixed
     * @throws HttpException
     */
    public function actionThumbProduct()
    {
        $model = new ProductModel();

        $model->setAttributes($this->getRequestGet(), false);
        if ($model->validate()) {
            return $this->service->thumbProduct($model);
        } else {
            throw new  FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, parent::getModelError($model));
        }
    }

    /**
     * 取消点赞
     * @return mixed
     * @throws HttpException
     */
    public function actionCancelThumbProduct()
    {
        $query = new ProductModel();
        $query->setAttributes($this->getRequestGet(), false);
        if ($query->validate()) {
            return $this->service->cancelThumbProduct($query);
        } else {
            throw new  FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, parent::getModelError($query));
        }
    }

    /**
     * 分页获取列表
     * @return mixed
     * @throws HttpException
     */
    public function actionGetPage()
    {
        $query = new ProductQuery();
        $query->setAttributes($this->getRequestGet(), false);

        $type = $query->type;
        //1推荐优先  2分类优先  3疯抢排行  4精品推荐  5全网热榜(搜索)
        if ($query->validate()) {
            //根据分类id取商品ids,
            if ($query->categoryId) {

                $productIds= $this->getProductIdsByCategoryId($query->categoryId);

                //获取下级分类Ids---add by Jia ---at 20-9-26 13:00
//                $categoryIds=CategoryModel::find()
//                    ->select(['id'])
//                    ->where(['parent_id'=>$query->categoryId, 'status'=>StatusEnum::ENABLED ] )
//                    ->asArray()
//                    ->all();
//                if(count($categoryIds)){
//                    $categoryIds= implode(',',array_column($categoryIds,'id'));
//                    $query->categoryId=$query->categoryId.','.$categoryIds;
//                }
//                //获取下级分类Ids---add by Jia ---at 20-9-26 13:00
//
//
//                $cpQuery = new ProductCategoryQuery();
//                $cpQuery->category_id = QueryEnum::IN . $query->categoryId;
//
//                $productIds = $this->cpService->getProductIds($cpQuery);

                if (empty($productIds)) {
                    return parent::getEmptyPage();
                }
                $query->id = QueryEnum::IN . $productIds;
                unset($query->categoryId);
            }

            return $this->service->getProductPage($query);
        } else {
            throw new  FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, parent::getModelError($query));
        }
    }


    private function getProductIdsByCategoryId($categoryId)
    {
        //加入缓存
        $tokenId ='QUERY-PRODUCT-ID-'.$categoryId;
        $tokenContent=Yii::$app->cache->get($tokenId);
        if(!empty($tokenContent)){
            return json_decode($tokenContent);
        }
        //获取下级分类Ids---add by Jia ---at 20-9-26 13:00
        $categoryIds=CategoryModel::find()
            ->select(['id'])
            ->where(['parent_id'=>$categoryId, 'status'=>StatusEnum::ENABLED ] )
            ->asArray()
            ->all();
        if(count($categoryIds)){
            $categoryIds= implode(',',array_column($categoryIds,'id'));
            $categoryId=$categoryId.','.$categoryIds;
        }
        //获取下级分类Ids---add by Jia ---at 20-9-26 13:00
        $cpQuery = new ProductCategoryQuery();
        $cpQuery->category_id = QueryEnum::IN . $categoryId;
        $productIds = $this->cpService->getProductIds($cpQuery);
        //100个小时
        Yii::$app->cache->set($tokenId,json_encode($productIds),360000);

    }

    public function actionGetCount()
    {
        $query = new ProductQuery();
        $query->setAttributes($this->getRequestGet(), false);

        if ($query->validate()) {
            //根据分类id取商品ids,
            if ($query->categoryId) {

                //获取下级分类Ids---add by Jia ---at 20-9-26 13:00
                $categoryIds=CategoryModel::find()
                    ->select(['id'])
                    ->where(['parent_id'=>$query->categoryId, 'status'=>StatusEnum::ENABLED ] )
                    ->asArray()
                    ->all();
                if(count($categoryIds)){
                    $categoryIds= implode(',',array_column($categoryIds,'id'));
                    $query->categoryId=$query->categoryId.','.$categoryIds;
                }
                //获取下级分类Ids---add by Jia ---at 20-9-26 13:00


                $cpQuery = new ProductCategoryQuery();
                $cpQuery->category_id = QueryEnum::IN . $query->categoryId;

                $productIds = $this->cpService->getProductIds($cpQuery);

                if (empty($productIds)) {
                    return 0;
                }

                $query->id = QueryEnum::IN . $productIds;
                unset($query->category_id);
            }

            return $this->service->count($query);
        } else {
            throw new  FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, parent::getModelError($query));
        }
    }

    /**
     * 根据Id获取详情
     * @return mixed
     */
    public function actionGetById()
    {
        $id = parent::getRequestId();
        ProductModel::updateAll(['store_count' => new Expression('store_count+' . rand(0, 2))], ['id' => $id]);
        $result = $this->service->getOneWithSkuById($id, parent::getRequestId('pinkId'));
        return $result;
    }

    /**
     * 获取sku
     * @return mixed
     */
    public function actionGetSkuById()
    {
        $id = parent::getRequestId();
        $info = $this->service->getOneWithSkuById($id);

        $array['skuDetail'] = $info['skuDetail'];
        $array['tagSnap'] = $info['tagSnap'];
        $array['name'] = $info['name'];
        $array['id'] = $info['id'];
        $array['is_sku'] = $info['is_sku'];
        $array['images'] = $info['images'];
        $array['sale_price'] = $info['sale_price'];
        $array['origin_price'] = $info['origin_price'];
        $array['member_price'] = $info['member_price'];
        $array['stock_number'] = $info['stock_number'];

        return $array;
    }

    /**
     * 分享商品
     * @return mixed
     * @throws FanYouHttpException
     * @throws \Throwable
     */
    public function actionCreateShareImg()
    {
        if(empty(parent::getUserId())){
            throw new  FanYouHttpException(HttpErrorEnum::UNAUTHORIZED, '请登陆');
        }

        $shareInfo = new ShareProduct();
        $shareInfo->setAttributes($this->getRequestPost(), false);

        if (strlen($shareInfo->title) > 45) {
            $shareInfo->title = substr($shareInfo->title, 0, 46) . '..';
        }
        $keyType = $shareInfo->key_type;
        $keyId = md5($shareInfo->user_id . $shareInfo->product_id . $keyType);
        $shareUrl = $this->shareService->getShareUrl($keyId);
        if ($shareUrl) {
            return $shareUrl;
        }
        $appCodePath = $this->miniAppService->createMiniAppCode($keyId, $shareInfo->page_path);//,$shareInfo->scene,$shareInfo->page);

        if (empty($shareInfo->share_img)) { 
            $basicConfig = $this->configService->getConfigInfoValue(false, SystemConfigEnum::BASIC_CONFIG);
            $baseImg = $basicConfig[BasicConfigEnum::BASIC_SHARE_IMG]; 
            $config = array(
                'text' => $this->textPosition(number_format($shareInfo->price, 2), $shareInfo->title),
                'image' => array(
                    $this->miniAppCodeArray($appCodePath),
                    $this->mainImgArray($shareInfo->first_img),
                    $this->headImgArray(PhotoMerge::yuan_img($shareInfo->head_img))
                ),
                'background' => $baseImg,
            );
        } else {
            //合成之后的图片需要存入阿里云，下次进来的时候，如何获取地址，再次定位
            $config = array(
                'text' => $this->textPosition(number_format($shareInfo->price, 2), $shareInfo->title),
                'image' => array(
                    $this->miniAppCodeArray($appCodePath),
                    $this->headImgArray(PhotoMerge::yuan_img($shareInfo->head_img))
                ),
                'background' => $shareInfo->share_img,
            );
        }

        $filename = md5($shareInfo->user_id . $shareInfo->product_id) . '.jpg';
        $filePath = sys_get_temp_dir() . '/' . $filename;

        if (!PhotoMerge::createPoster($config, $filePath)) {
            throw  new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, ErrorProduct::SYSTEM_ERROR);
        }
        $upload = new UploadHelper(Yii::$app->request->post(), Attachment::UPLOAD_TYPE_IMAGES);
        $array = $upload->save(Yii::$app->params['userImage']['product-share'], $filePath, $filename);
        $shareUrl = $array['accessUrl'];
        $model = new UserShareModel();
        $model->id = $keyId;
        $model->user_id = (string)$shareInfo->user_id;
        $model->key_type = $keyType;
        $model->key_id = (string)$shareInfo->product_id;
        $model->share_url = $shareUrl;
        $this->shareService->addUserShare($model);
        return $shareUrl;

    }


    /**
     * 二维码资源位置
     * @param $appCodePath
     * @return array
     */
    private function miniAppCodeArray($appCodePath): array
    {
        return array(
            'url' => $appCodePath,
            'stream' => 0,
            'left'=>$_ENV['IMG_PRODUCT_LEFT'],
            'top'=>$_ENV['IMG_PRODUCT_TOP'],
            'right' => 0,
            'bottom' => 0,
            'width' => 166,
            'height' => 166,
            'opacity' => 100
        );
    }

    /**
     * 头像位置
     * @param $headImgPath
     * @return array
     */
    private function headImgArray($headImgPath): array
    {
        return array(
            'url' => $headImgPath,
            'stream' => 0,
            'left'=>$_ENV['IMG_PRODUCT_HEAD_LEFT'],
            'top'=>$_ENV['IMG_PRODUCT_HEAD_TOP'],
            'right' => 0,
            'bottom' => 0,
            'width' => 110,
            'height' => 110,
            'opacity' => 99
        );
    }

    private function mainImgArray($headImgPath): array
    {
        return array(
            'url' => $headImgPath,
            'stream' => 0,
            'left' => 24,
            'top' => 167,
            'right' => 0,
            'bottom' => 0,
            'width' => 702,
            'height' => 700,
            'opacity' => 100
        );
    }

    private function textPosition($price, $title)
    {
        return array(
            array(
                'text' => $price,
                 'left'=>$_ENV['TXT_PRODUCT_LEFT'],
                 'top'=>$_ENV['TXT_PRODUCT_TOP'],
                'fontSize' => 32,       //字号
                'fontColor' =>$_ENV['TXT_PRODUCT_COLOR'],// Yii::$app->params['shareImg']['product']['fontColor'], //字体颜色
                'angle' => 0,
                'fontPath' => $this->actionGetNumberFont(),
            ),
            array(
                'text' => $title,
                'left' => 300,
                'top' => 939,
                'fontSize' => 16,       //字号
                'fontColor' => '0,0,0', //字体颜色
                'angle' => 0,
                'fontPath' => $this->actionGetAliFont(),
            )
        );
    }
}
/**********************End Of Product 控制层************************************/ 



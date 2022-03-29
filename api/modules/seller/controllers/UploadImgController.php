<?php

namespace api\modules\seller\controllers;

use api\modules\auth\ApiAuth;
use fanyou\models\common\Attachment;
use fanyou\tools\UploadHelper;
use Yii;


/**
 * Class PlantController
 * @package api\modules\plant_v1\controllers
 */
class UploadImgController extends  BaseController
{
    protected $config = [];
    public function init()
    {
        parent::init();
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => ApiAuth::class,
        ];
        return $behaviors;
    }

    /**
     * 上传分类图片
     * @return mixed
     * @throws \Throwable
     */
    public function actionAddCategoryImage()
    {
        $upload = new UploadHelper(Yii::$app->request->post(), Attachment::UPLOAD_TYPE_IMAGES);
        return $upload->save(Yii::$app->params['categoryUploadConfig']['img']);
    }

    /**
     * 上传商品图片
     * @return mixed
     * @throws \Throwable
     */
    public function actionAddProductImage()
    {
        $upload = new UploadHelper(Yii::$app->request->post(), Attachment::UPLOAD_TYPE_IMAGES);
        return $upload->save(Yii::$app->params['productUploadConfig']['img']);
    }

    /**
     * 上传商品视频
     * @return mixed
     * @throws \Throwable
     */
    public function actionAddProductVideo()
    {
        $upload = new UploadHelper(Yii::$app->request->post(), Attachment::UPLOAD_TYPE_VIDEOS);
        return $upload->save(Yii::$app->params['productUploadConfig']['video']);
    }

    /**
     * 上传资讯图片
     * @return mixed
     * @throws \Throwable
     */
    public function actionAddInformationImage()
    {
        $upload = new UploadHelper(Yii::$app->request->post(), Attachment::UPLOAD_TYPE_IMAGES);
        return $upload->save(Yii::$app->params['informationUploadConfig']['img']);
    }

    /**
     * 上传系统配置文件图片
     * @return mixed
     * @throws \Throwable
     */
    public function actionAddSystemImage()
    {  $upload = new UploadHelper(Yii::$app->request->post(), Attachment::UPLOAD_TYPE_IMAGES);
        return  $upload->save(Yii::$app->params['systemConfigImage']['img']);
    }

    /**
     * 上传富文本的图片
     * @return mixed
     * @throws \Throwable
     */
    public function actionAddProductRichTextImage()
    {
        $upload = new UploadHelper(Yii::$app->request->post(), Attachment::UPLOAD_TYPE_IMAGES);
        return  $upload->save(Yii::$app->params['productUploadConfig']['rich-text']);
    }
    /**
     * 上传店铺图片
     */
    public function actionAddShopImage()
    {
        $upload = new UploadHelper(Yii::$app->request->post(), Attachment::UPLOAD_TYPE_IMAGES);
        return  $upload->save(Yii::$app->params['shopUploadConfig']['img']);
    }
    /**
     * 上传区域图片
     */
    public function actionAddAreaImage()
    {   $upload = new UploadHelper(Yii::$app->request->post(), Attachment::UPLOAD_TYPE_IMAGES);
        return  $upload->save(Yii::$app->params['areaImageUploadConfig']['accounting']);
    }

}

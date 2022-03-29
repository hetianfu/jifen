<?php

namespace api\modules\seller\controllers;

use fanyou\models\common\Attachment;
use fanyou\tools\UploadHelper;
use Yii;


/**
 * Class UploadFileController
 * @package api\modules\seller\controllers
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-07-10 16:54
 */
class UploadFileController extends  BaseController
{
    protected $config = [];
    public function init()
    {
        parent::init();
    }
    /**
     * 上传微信证书
     * @return mixed
     * @throws \Throwable
     */
    public function actionUploadWxPayCert()
    {
        $upload = new UploadHelper(Yii::$app->request->post(), Attachment::UPLOAD_TYPE_FILES);
        $realUpload=($_ENV['IS_SERVICE_PAY']=='true') ?false:true;
        $filePath = $upload->saveCert($realUpload)['accessUrl'];
        return $filePath;
    }


    /**
     * 上传静态资源
     * @return mixed
     * @throws \Throwable
     */
    public function actionUploadStaticImg()
    {
        $upload = new UploadHelper(Yii::$app->request->post(), Attachment::UPLOAD_TYPE_IMAGES);
        return $upload->save(Yii::$app->request->post("path"),'',Yii::$app->request->post("name"));

    }

}

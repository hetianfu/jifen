<?php
namespace api\modules\mobile\controllers;

use api\modules\auth\ApiAuth;
use api\modules\mobile\service\UserInfoService;
use fanyou\models\common\Attachment;
use fanyou\tools\UploadHelper;
use Yii;

/**
 * UserInfo
 * @author  Round
 * @E-mail: Administrator@qq.com
 *
 */
class UploadImgController extends BaseController
{

    private $service; 
    public function init()
    {
        parent::init();
        $this->service = new UserInfoService();
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => ApiAuth::class,
            'optional'=>['upload-reply']
        ];
        return $behaviors;
    }
/*********************UserInfo模块控制层************************************/

    /**
     * 上传商品评论
     * @return mixed
     * @throws \Throwable
     */
	public function actionUploadReply(){

        $upload = new UploadHelper(Yii::$app->request->post(), Attachment::UPLOAD_TYPE_IMAGES);

        return $upload->save(Yii::$app->params['productUploadConfig']['reply']);
	}

	}
/**********************End Of UserInfo 控制层************************************/ 



<?php
namespace api\modules\mobile\controllers;

use api\modules\auth\ApiAuth;
use api\modules\mobile\models\forms\UserShareModel;
use api\modules\mobile\service\UserShareService;
use Yii;

/**
 * Class UserShareController
 * @package api\modules\mobile\controllers
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-06-24 14:23
 */
class UserShareController extends BaseController
{

    private $service;
    public function init()
    {
        parent::init();
        $this->service=new UserShareService();
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => ApiAuth::class,
            'optional'=>['get-by-id','get-share-code']
        ];
        return $behaviors;
    }
/*********************UserShopCart模块控制层************************************/
    /**
     * 取单条数据
     * @return mixed
     */
	public function actionGetById()
	{
//	    for ($key=0;$key<100000;$key++){
//            $this->service->getOneById(parent::getRequestId());
//        }

		return $this->service->getOneById(parent::getRequestId());

	}

    /**
     * 获取商品分享码
     * @return mixed
     */
//    public function actionGetProductShareCode()
//    {
//        $model=new  UserShareModel();
//        $productId= Yii::$app->request->get('productId');
//        $keyId=md5(parent::getUserId().$productId.ShareTypeEnum::PRODUCT);
//        $model->id=$keyId;
//        $model->user_id=parent::getUserId();
//        $model->key_id=$productId;
//        $model->key_type=ShareTypeEnum::PRODUCT;
//        $id= $this->service->addUserShare($model);
//        if($id){
//            return $id;
//        }
//        return $this->service->getOne(parent::getUserId(),$productId,ShareTypeEnum::PRODUCT);
//    }
    /**
     * 获取分享码
     * @return mixed
     */
    public function actionGetShareCode()
    {
        $model=new  UserShareModel();
        $type=Yii::$app->request->get('keyType');
        $keyId=Yii::$app->request->get('keyId');
        if(empty($keyId)){
            $keyId=parent::getUserId();
        }
        $keyId=md5(parent::getUserId().$keyId.$type);
        $model->id=$keyId;
        $model->user_id=parent::getUserId();
        $model->key_id=$keyId;
        $model->key_type=$type;
        $id= $this->service->addUserShare($model);
        if($id){
            return $id;
        }
        return $this->service->getOne(parent::getUserId(),$keyId,$type);

    }
    public function actionGetOneByType ()
    {
        return $this->service->getOne(parent::getUserId(),parent::getRequestId(),Yii::$app->request->get('type'));

    }
	}
/**********************End Of UserShopCart 控制层************************************/



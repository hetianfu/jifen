<?php
namespace api\modules\seller\controllers;

use api\modules\auth\ApiAuth;
use api\modules\seller\service\ShopUserService;
use api\modules\seller\models\forms\ShopUserModel;
use api\modules\seller\models\forms\ShopUserQuery;

use Yii;
use yii\web\HttpException;

/**
 * ShopUser
 * @author E-mail: Administrator@qq.com
 *
 */
class ShopUserController extends BaseController
{

    private $service; 
    public function init()
    {
        parent::init();
        $this->service = new ShopUserService();
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => ApiAuth::class,
        ];
        return $behaviors;
    }
/*********************ShopUser模块控制层************************************/ 
	/**
	 * 添加
     * @return mixed
     * @throws HttpException
     */
	public function actionAddShopUser()
	{
		$model = new ShopUserModel();
		$model->setAttributes($this->getRequestPost()) ;
		if ($model->validate()) {
			return $this->service->addShopUser($model);
		} else {
		   throw new \yii\web\UnprocessableEntityHttpException(parent::getModelError($model));
		}
	}
	/**
	 * 分页获取列表
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionGetShopUserPage()
	{
		$query = new ShopUserQuery();
		$query->setAttributes($this->getRequestGet());
		if ( $query->validate()) {
			return $this->service->getShopUserPage( $query);
		} else {
		   throw new \yii\web\UnprocessableEntityHttpException(parent::getModelError($query));
		}
	}


	/**
	 * 根据Id获取详情
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionGetShopUserById(){
		$id = Yii::$app->request->get('id');
		return $this->service->getShopUserById($id);
	}
	/**
	 * 根据Id更新
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionUpdateShopUser(){

		$model = new ShopUserModel(['scenario'=>'update']);
		$model->setAttributes($this->getRequestPost(false)) ;
		if ($model->validate()) {
			return $this->service->updateShopUserById($model);
		} else {
		  throw new \yii\web\UnprocessableEntityHttpException(parent::getModelError($model));
	   }
	}

    /**
     * 将用户拉入黑名单
     * @return mixed
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function actionUpdateBatchBlack(){

        $model = new ShopUserModel(['scenario'=>'update']);
        $model->id=Yii::$app->request->post('id') ;
        $model->status=-1;
        if ($model->validate()) {
            return $this->service->updateShopUserById($model);
        } else {
            throw new \yii\web\UnprocessableEntityHttpException(parent::getModelError($model));
        }
    }
    /**
     * 将用户移出黑名单
     * @return mixed
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function actionUpdateBatchWhite(){

        $model = new ShopUserModel(['scenario'=>'update']);
        $model->id=Yii::$app->request->post('id') ;
        $model->status=1;
        if ($model->validate()) {
            return $this->service->updateShopUserById($model);
        } else {
            throw new \yii\web\UnprocessableEntityHttpException(parent::getModelError($model));
        }
    }
	/**
	 * 根据Id删除
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionDelShopUser(){

		$id = Yii::$app->request->get('id');
		return $this->service->deleteById($id);
		}
	}
/**********************End Of ShopUser 控制层************************************/ 



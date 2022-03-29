<?php
namespace api\modules\seller\controllers;

use api\modules\auth\ApiAuth;
use api\modules\seller\service\UserAddressService;
use api\modules\seller\models\forms\UserAddressModel;
use api\modules\seller\models\forms\UserAddressQuery;

use fanyou\enums\HttpErrorEnum;
use fanyou\error\FanYouHttpException;
use Yii;
use yii\web\HttpException;

/**
 * UserAddress
 * @author E-mail: Administrator@qq.com
 *
 */
class UserAddressController extends BaseController
{

    private $service; 
    public function init()
    {
        parent::init();
        $this->service = new UserAddressService();
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => ApiAuth::class,
        ];
        return $behaviors;
    }
/*********************UserAddress模块控制层************************************/ 
	/**
	 * 添加
     * @return mixed
     * @throws HttpException
     */
	public function actionAddUserAddress()
	{
		$model = new UserAddressModel();
		$model->setAttributes($this->getRequestPost()) ;
		if ($model->validate()) {
			return $this->service->addUserAddress($model);
		} else {
		   throw new \yii\web\UnprocessableEntityHttpException(parent::getModelError($model));
		}
	}
	/**
	 * 分页获取列表
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionGetPage()
	{
		$query = new UserAddressQuery();
		$query->setAttributes($this->getRequestGet());
		if ( $query->validate()) {
			return $this->service->getUserAddressPage( $query);
		} else {
            throw new  FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, parent::getModelError($query));
		}
	}


	/**
	 * 根据Id获取详情
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionGetUserAddressById(){
		$id = Yii::$app->request->get('id');
		return $this->service->getUserAddressById($id);
	}
	/**
	 * 根据Id更新
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionUpdateUserAddress(){

		$model = new UserAddressModel(['scenario'=>'update']);
		$model->setAttributes($this->getRequestPost(false)) ;
		if ($model->validate()) {
			return $this->service->updateUserAddressById($model);
		} else {
		  throw new \yii\web\UnprocessableEntityHttpException(parent::getModelError($model));
	   }
	}

	/**
	 * 根据Id删除
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionDelUserAddress(){

		$id = Yii::$app->request->get('id');
		return $this->service->deleteById($id);
		}
	}
/**********************End Of UserAddress 控制层************************************/ 



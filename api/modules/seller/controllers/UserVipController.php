<?php
namespace api\modules\seller\controllers;

use api\modules\auth\ApiAuth;
use api\modules\seller\models\forms\UserVipDetailQuery;
use api\modules\seller\models\forms\UserVipPayModel;
use api\modules\seller\models\forms\UserVipPayQuery;
use api\modules\seller\service\UserVipPayService;
use api\modules\seller\service\UserVipService;
use api\modules\seller\models\forms\UserVipModel;
use api\modules\seller\models\forms\UserVipQuery;
use fanyou\error\FanYouHttpException;
use fanyou\enums\HttpErrorEnum;
use Yii;
use yii\web\HttpException;

/**
 * Class UserVipController
 * @package api\modules\seller\controllers
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-06-16 15:00
 */
class UserVipController extends BaseController
{

    private $service;
    private $payService;
    public function init()
    {
        parent::init();
        $this->service = new UserVipService();
        $this->payService = new UserVipPayService();
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => ApiAuth::class,
        ];
        return $behaviors;
    }
/*********************UserVip模块控制层************************************/ 
	/**
	 * 添加
     * @return mixed
     * @throws HttpException
     */
	public function actionAddUserVip()
	{
		$model = new UserVipModel();
		$model->setAttributes($this->getRequestPost()) ;
		if ($model->validate()) {
			return $this->service->addUserVip($model);
		} else {
			throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($model));
		}
	}
	/**
	 * 分页获取列表
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionGetUserVipPage()
	{
		$query = new UserVipQuery();
		$query->setAttributes($this->getRequestGet());
		if ( $query->validate()) {
			return $this->service->getUserVipPage( $query);
		} else {
			throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($query));
		}
	}


	/**
	 * 根据Id获取详情
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionGetUserVipById(){
		return $this->service->getOneById(parent::getRequestId());
	}
	/**
	 * 根据Id更新
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionUpdateUserVip(){

		$model = new UserVipModel(['scenario'=>'update']);
		$model->setAttributes($this->getRequestPost(false)) ;
		if ($model->validate()) {
			return $this->service->updateUserVipById($model);
		} else {
			throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($model));
	   }
	}

	/**
	 * 根据Id删除
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionDelUserVip(){

		return $this->service->deleteById(parent::getRequestId());
		}



    /**
     * 添加
     * @return mixed
     * @throws HttpException
     */
    public function actionAddUserVipPay()
    {
        $model = new UserVipPayModel();
        $model->setAttributes($this->getRequestPost()) ;
        if ($model->validate()) {
            return $this->payService->addUserVipPay($model);
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($model));
        }
    }
    /**
     * 根据Id更新
     * @return mixed
     * @throws HttpException
     */
    public function actionUpdateUserVipPayById(){

        $model = new UserVipPayModel(['scenario'=>'update']);
        $model->setAttributes($this->getRequestPost(false)) ;
        if ($model->validate()) {
            return $this->payService->updateUserVipPayById($model);
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($model));
        }
    }

    /**
     * 根据Id删除
     * @return mixed
     */
    public function actionDelUserVipPay(){

        return $this->payService->deleteById(parent::getRequestId());
    }
    /**
     * 分页获取列表
     * @return mixed
     * @throws HttpException
     */
    public function actionGetUserVipPayPage()
    {
        $query = new UserVipPayQuery();
        $query->setAttributes($this->getRequestGet());
        if ( $query->validate()) {


            return $this->payService->getUserVipPayPage( $query);
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($query));
        }
    }

    public function actionGetVipDetailPage()
    {
        $query = new UserVipDetailQuery();
        $query->setAttributes($this->getRequestGet());
        if ( $query->validate()) {
            return $this->payService->getVipDetailPage( $query);
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($query));
        }
    }

	}
/**********************End Of UserVip 控制层************************************/ 



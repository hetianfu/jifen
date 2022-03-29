<?php
namespace api\modules\seller\controllers;

use api\modules\auth\ApiAuth;
use api\modules\seller\service\AppVersionService;
use api\modules\seller\models\forms\AppVersionModel;
use api\modules\seller\models\forms\AppVersionQuery;
use fanyou\error\FanYouHttpException;
use fanyou\enums\HttpErrorEnum;
use Yii;
use yii\web\HttpException;

/**
 * Class AppVersionController
 * @package api\modules\seller\controllers
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-07-13
 */
class AppVersionController extends BaseController
{

    private $service; 
    public function init()
    {
        parent::init();
        $this->service = new AppVersionService();
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => ApiAuth::class,
        ];
        return $behaviors;
    }
/*********************AppVersion模块控制层************************************/ 
	/**
	 * 添加
     * @return mixed
     * @throws HttpException
     */
	public function actionAddAppVersion()
	{
		$model = new AppVersionModel();
		$model->setAttributes($this->getRequestPost()) ;
		if ($model->validate()) {
			return $this->service->addAppVersion($model);
		} else {
			throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($model));
		}
	}
	/**
	 * 分页获取列表
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionGetAppVersionPage()
	{
		$query = new AppVersionQuery();
		$query->setAttributes($this->getRequestGet());
		if ( $query->validate()) {
			return $this->service->getAppVersionPage( $query);
		} else {
			throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($query));
		}
	}


	/**
	 * 根据Id获取详情
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionGetAppVersionById(){
		return $this->service->getOneById(parent::getRequestId());
	}
	/**
	 * 根据Id更新
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionUpdateAppVersion(){

		$model = new AppVersionModel(['scenario'=>'update']);
		$model->setAttributes($this->getRequestPost(false)) ;
		if ($model->validate()) {
			return $this->service->updateAppVersionById($model);
		} else {
			throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($model));
	   }
	}

	/**
	 * 根据Id删除
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionDelAppVersion(){

		return $this->service->deleteById(parent::getRequestId());
		}
	}
/**********************End Of AppVersion 控制层************************************/ 



<?php
namespace api\modules\seller\controllers;

use api\modules\auth\ApiAuth;
use api\modules\seller\models\forms\MerchantInfoModel;
use api\modules\seller\service\MerchantInfoService;
use fanyou\enums\HttpErrorEnum;
use fanyou\error\FanYouHttpException;
use yii\web\HttpException;

/**
 * MerchantInfo
 * @author E-mail: Administrator@qq.com
 *
 */
class MerchantController extends BaseController
{

    private $service;
    public function init()
    {
        parent::init();
        $this->service = new MerchantInfoService();
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => ApiAuth::class,
        ];
        return $behaviors;
    }
/*********************MerchantInfo模块控制层************************************/

	/**
	 * 根据Id获取详情
	 * @return mixed
	 */
	public function actionGetMerchantInfo(){

		return $this->service->getOneById(parent::getMerchantId());
	}
	/**
	 * 根据Id更新
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionUpdateMerchantInfo(){

		$model = new MerchantInfoModel(['scenario'=>'update']);
		$model->setAttributes($this->getRequestPost(false)) ;
        $model->id=parent::getMerchantId();
		if ($model->validate()) {
			return $this->service->updateMerchantInfoById($model);
		} else {
		  throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($model));
	   }
	}
	}
/**********************End Of MerchantInfo 控制层************************************/ 



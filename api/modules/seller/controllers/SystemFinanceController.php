<?php
namespace api\modules\seller\controllers;

use api\modules\auth\ApiAuth;
use api\modules\seller\service\SystemFinanceService;
use api\modules\seller\models\forms\SystemFinanceModel;
use api\modules\seller\models\forms\SystemFinanceQuery;
use fanyou\error\FanYouHttpException;
use fanyou\enums\HttpErrorEnum;
use yii\web\HttpException;

/**
 * Class SystemFinanceController
 * @package api\modules\seller\controllers
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-06-16
 */
class SystemFinanceController extends BaseController
{

    private $service; 
    public function init()
    {
        parent::init();
        $this->service = new SystemFinanceService();
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => ApiAuth::class,
            'optional'=>['get-system-finance-page']
        ];
        return $behaviors;
    }
/*********************SystemFinance模块控制层************************************/ 
	/**
	 * 添加
     * @return mixed
     * @throws HttpException
     */
	public function actionAddSystemFinance()
	{
		$model = new SystemFinanceModel();
		$model->setAttributes($this->getRequestPost()) ;
		if ($model->validate()) {
			return $this->service->addSystemFinance($model);
		} else {
			throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($model));
		}
	}
	/**
	 * 分页获取列表
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionGetSystemFinancePage()
	{
		$query = new SystemFinanceQuery();
		$query->setAttributes($this->getRequestGet());
		if ( $query->validate()) {
			return $this->service->getSystemFinancePage( $query);
		} else {
			throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($query));
		}
	}


	/**
	 * 根据Id获取详情
	 * @return mixed
	 */
	public function actionGetSystemFinanceById(){
		return $this->service->getOneById(parent::getRequestId());
	}
	/**
	 * 根据Id更新
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionUpdateSystemFinance(){

		$model = new SystemFinanceModel(['scenario'=>'update']);
		$model->setAttributes($this->getRequestPost(false)) ;
		if ($model->validate()) {
			return $this->service->updateSystemFinanceById($model);
		} else {
			throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($model));
	   }
	}

	/**
	 * 根据Id删除
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionDelSystemFinance(){

		return $this->service->deleteById(parent::getRequestId());
		}
	}
/**********************End Of SystemFinance 控制层************************************/ 



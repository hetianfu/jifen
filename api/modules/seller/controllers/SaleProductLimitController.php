<?php
namespace api\modules\seller\controllers;

use api\modules\auth\ApiAuth;
use api\modules\seller\service\SaleProductLimitService;
use api\modules\seller\models\forms\SaleProductLimitModel;
use api\modules\seller\models\forms\SaleProductLimitQuery;
use fanyou\error\FanYouHttpException;
use fanyou\enums\HttpErrorEnum;
use Yii;
use yii\web\HttpException;

/**
 * Class SaleProductLimitController
 * @package api\modules\seller\controllers
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-08-26
 */
class SaleProductLimitController extends BaseController
{

    private $service; 
    public function init()
    {
        parent::init();
        $this->service = new SaleProductLimitService();
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => ApiAuth::class,
        ];
        return $behaviors;
    }
/*********************SaleProductLimit模块控制层************************************/ 
	/**
	 * 添加
     * @return mixed
     * @throws HttpException
     */
	public function actionAddSaleProductLimit()
	{
		$model = new SaleProductLimitModel();
		$model->setAttributes($this->getRequestPost()) ;
		if ($model->validate()) {
			return $this->service->addSaleProductLimit($model);
		} else {
			throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($model));
		}
	}
	/**
	 * 分页获取列表
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionGetSaleProductLimitPage()
	{
		$query = new SaleProductLimitQuery();
		$query->setAttributes($this->getRequestGet());
		if ( $query->validate()) {
			return $this->service->getSaleProductLimitPage( $query);
		} else {
			throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($query));
		}
	}


	/**
	 * 根据Id获取详情
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionGetSaleProductLimitById(){
		return $this->service->getOneById(parent::getRequestId());
	}
	/**
	 * 根据Id更新
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionUpdateSaleProductLimit(){

		$model = new SaleProductLimitModel(['scenario'=>'update']);
		$model->setAttributes($this->getRequestPost(false)) ;
		if ($model->validate()) {
			return $this->service->updateSaleProductLimitById($model);
		} else {
			throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($model));
	   }
	}

	/**
	 * 根据Id删除
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionDelSaleProductLimit(){

		return $this->service->deleteById(parent::getRequestId());
		}
	}
/**********************End Of SaleProductLimit 控制层************************************/ 



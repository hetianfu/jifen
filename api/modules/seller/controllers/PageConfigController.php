<?php
namespace api\modules\seller\controllers;

use api\modules\auth\ApiAuth;
use api\modules\seller\service\PagConfigService;
use api\modules\seller\models\forms\PagConfigModel;
use api\modules\seller\models\forms\PagConfigQuery;
use fanyou\enums\SystemConfigEnum;
use fanyou\error\FanYouHttpException;
use fanyou\enums\HttpErrorEnum;
use fanyou\tools\ArrayHelper;
use Yii;
use yii\web\HttpException;

/**
 * Class PagConfigController
 * @package api\modules\seller\controllers
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-08-18
 */
class PageConfigController extends BaseController
{

    private $service; 
    public function init()
    {
        parent::init();
        $this->service = new PagConfigService();
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => ApiAuth::class,
        ];
        return $behaviors;
    }
/*********************PagConfig模块控制层************************************/ 
	/**
	 * 添加
     * @return mixed
     * @throws HttpException
     */
	public function actionAddPageConfig()
	{
		$model = new PagConfigModel();
		$model->setAttributes($this->getRequestPost()) ;
		if ($model->validate()) {
			return $this->service->addPagConfig($model);
		} else {
			throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($model));
		}
	}
	/**
	 * 分页获取列表
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionGetPageConfigPage()
	{
		$query = new PagConfigQuery();
		$query->setAttributes($this->getRequestGet());

		if ( $query->validate()) {
			return $this->service->getPagConfigPage( $query);
		} else {
			throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($query));
		}
	}


	/**
	 * 根据Id获取详情
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionGetPageConfigById(){
		return ArrayHelper::toArray($this->service->getOneById(parent::getRequestId()));
	}
	/**
	 * 根据Id更新
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionUpdatePageConfigById(){

		$model = new PagConfigModel(['scenario'=>'update']);
		$model->setAttributes($this->getRequestPost(false)) ;
		if ($model->validate()) {
			return $this->service->updatePagConfigById($model);
		} else {
			throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($model));
	   }
	}

	/**
	 * 根据Id删除
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionDelPageConfigById(){

		return $this->service->deleteById(parent::getRequestId());
		}
	}
/**********************End Of PagConfig 控制层************************************/ 



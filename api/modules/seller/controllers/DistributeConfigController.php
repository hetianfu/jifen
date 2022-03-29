<?php
namespace api\modules\seller\controllers;

use api\modules\auth\ApiAuth;
use api\modules\seller\service\DistributeConfigService;
use api\modules\seller\models\forms\DistributeConfigModel;
use api\modules\seller\models\forms\DistributeConfigQuery;

use Yii;
use yii\web\HttpException;

/**
 * DistributeConfig
 * @author E-mail: Administrator@qq.com
 *
 */
class DistributeConfigController extends BaseController
{

    private $service; 
    public function init()
    {
        parent::init();
        $this->service = new DistributeConfigService();
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => ApiAuth::class,
        ];
        return $behaviors;
    }
/*********************DistributeConfig模块控制层************************************/ 
	/**
	 * 添加
     * @return mixed
     * @throws HttpException
     */
	public function actionAddDistributeConfig()
	{
		$model = new DistributeConfigModel();
		$model->setAttributes($this->getRequestPost()) ;
		if ($model->validate()) {
			return $this->service->addDistributeConfig($model);
		} else {
		   throw new \yii\web\UnprocessableEntityHttpException(parent::getModelError($model));
		}
	}
	/**
	 * 分页获取列表
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionGetDistributeConfigPage()
	{
		$query = new DistributeConfigQuery();
		$query->setAttributes($this->getRequestGet());
		if ( $query->validate()) {
			return $this->service->getDistributeConfigPage( $query);
		} else {
		   throw new \yii\web\UnprocessableEntityHttpException(parent::getModelError($query));
		}
	}


	/**
	 * 根据Id获取详情
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionGetDistributeConfigById(){
		$id = Yii::$app->request->get('id');
		return $this->service->getDistributeConfigById($id);
	}
	/**
	 * 根据Id更新
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionUpdateDistributeConfig(){

		$model = new DistributeConfigModel(['scenario'=>'update']);
		$model->setAttributes($this->getRequestPost(false)) ;
		if ($model->validate()) {
			return $this->service->updateDistributeConfigById($model);
		} else {
		  throw new \yii\web\UnprocessableEntityHttpException(parent::getModelError($model));
	   }
	}

	/**
	 * 根据Id删除
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionDelDistributeConfig(){

		$id = Yii::$app->request->get('id');
		return $this->service->deleteById($id);
		}
	}
/**********************End Of DistributeConfig 控制层************************************/ 



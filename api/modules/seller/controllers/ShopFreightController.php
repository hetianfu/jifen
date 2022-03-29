<?php
namespace api\modules\seller\controllers;

use api\modules\auth\ApiAuth;
use api\modules\seller\service\ShopFreightService;
use api\modules\seller\models\forms\ShopFreightModel;
use api\modules\seller\models\forms\ShopFreightQuery;

use Yii;
use yii\web\HttpException;

/**
 * ShopFreight
 * @author E-mail: Administrator@qq.com
 *
 */
class ShopFreightController extends BaseController
{

    private $service; 
    public function init()
    {
        parent::init();
        $this->service = new ShopFreightService();
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => ApiAuth::class,
        ];
        return $behaviors;
    }
/*********************ShopFreight模块控制层************************************/ 
	/**
	 * 添加
     * @return mixed
     * @throws HttpException
     */
	public function actionAddShopFreight()
	{
		$model = new ShopFreightModel();
		$model->setAttributes($this->getRequestPost()) ;
		if ($model->validate()) {
			return $this->service->addShopFreight($model);
		} else {
		   throw new \yii\web\UnprocessableEntityHttpException(parent::getModelError($model));
		}
	}
	/**
	 * 分页获取列表
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionGetShopFreightPage()
	{
		$query = new ShopFreightQuery();
		$query->setAttributes($this->getRequestGet());
		if ( $query->validate()) {
			return $this->service->getShopFreightPage( $query);
		} else {
		   throw new \yii\web\UnprocessableEntityHttpException(parent::getModelError($query));
		}
	}


	/**
	 * 根据Id获取详情
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionGetShopFreightById(){
		$id = Yii::$app->request->get('id');
		return $this->service->getShopFreightById($id);
	}
	/**
	 * 根据Id更新
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionUpdateShopFreight(){

		$model = new ShopFreightModel(['scenario'=>'update']);
		$model->setAttributes($this->getRequestPost(false)) ;
		if ($model->validate()) {
			return $this->service->updateShopFreightById($model);
		} else {
		  throw new \yii\web\UnprocessableEntityHttpException(parent::getModelError($model));
	   }
	}

	/**
	 * 根据Id删除
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionDelShopFreight(){

		$id = Yii::$app->request->get('id');
		return $this->service->deleteById($id);
		}
	}
/**********************End Of ShopFreight 控制层************************************/ 



<?php
namespace api\modules\seller\controllers;

use api\modules\auth\ApiAuth;
use api\modules\seller\service\ProductKillService;
use api\modules\seller\models\forms\ProductKillModel;
use api\modules\seller\models\forms\ProductKillQuery;

use Yii;
use yii\web\HttpException;

/**
 * ProductKill
 * @author E-mail: Administrator@qq.com
 *
 */
class ProductKillController extends BaseController
{

    private $service; 
    public function init()
    {
        parent::init();
        $this->service = new ProductKillService();
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => ApiAuth::class,
        ];
        return $behaviors;
    }
/*********************ProductKill模块控制层************************************/ 
	/**
	 * 添加
     * @return mixed
     * @throws HttpException
     */
	public function actionAddProductKill()
	{
		$model = new ProductKillModel();
		$model->setAttributes($this->getRequestPost()) ;
		if ($model->validate()) {
			return $this->service->addProductKill($model);
		} else {
		   throw new \yii\web\UnprocessableEntityHttpException(parent::getModelError($model));
		}
	}
	/**
	 * 分页获取列表
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionGetProductKillPage()
	{
		$query = new ProductKillQuery();
		$query->setAttributes($this->getRequestGet());
		if ( $query->validate()) {
			return $this->service->getProductKillPage( $query);
		} else {
		   throw new \yii\web\UnprocessableEntityHttpException(parent::getModelError($query));
		}
	}


	/**
	 * 根据Id获取详情
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionGetProductKillById(){
		$id = Yii::$app->request->get('id');
		return $this->service->getProductKillById($id);
	}
	/**
	 * 根据Id更新
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionUpdateProductKill(){

		$model = new ProductKillModel(['scenario'=>'update']);
		$model->setAttributes($this->getRequestPost(false)) ;
		if ($model->validate()) {
			return $this->service->updateProductKillById($model);
		} else {
		  throw new \yii\web\UnprocessableEntityHttpException(parent::getModelError($model));
	   }
	}

	/**
	 * 根据Id删除
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionDelProductKill(){

		$id = Yii::$app->request->get('id');
		return $this->service->deleteById($id);
		}
	}
/**********************End Of ProductKill 控制层************************************/ 



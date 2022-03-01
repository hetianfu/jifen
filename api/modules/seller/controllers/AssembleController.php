<?php
namespace api\modules\seller\controllers;

use api\modules\auth\ApiAuth;
use api\modules\seller\models\forms\ProductAssembleConfigModel;
use api\modules\seller\models\forms\ProductAssembleConfigQuery;
use api\modules\seller\models\forms\ProductAssembleModel;
use api\modules\seller\models\forms\ProductAssembleQuery;
use api\modules\seller\service\AssembleService;
use api\modules\seller\service\ProductAssembleConfigService;
use fanyou\enums\HttpErrorEnum;
use fanyou\error\FanYouHttpException;
use Yii;
use yii\web\HttpException;

/**
 * ProductAssembleConfig
 * @author E-mail: Administrator@qq.com
 *
 */
class AssembleController extends BaseController
{

    private $service; 
    public function init()
    {
        parent::init();
        $this->service = new  AssembleService();
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => ApiAuth::class,
        ];
        return $behaviors;
    }
/*********************ProductAssembleConfig模块控制层************************************/ 
	/**
	 * 添加
     * @return mixed
     * @throws HttpException
     */
	public function actionAddAssembleConfig()
	{
		$model = new ProductAssembleConfigModel();
		$model->setAttributes($this->getRequestPost()) ;
		if ($model->validate()) {
			return $this->service->addProductAssembleConfig($model);
		} else {
		   throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($model));
		}
	}
	/**
	 * 分页获取列表
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionGetAssembleConfigPage()
	{
		$query = new ProductAssembleConfigQuery();
		$query->setAttributes($this->getRequestGet());
		if ( $query->validate()) {
			return $this->service->getProductAssembleConfigPage( $query);
		} else {
		   throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($query));
		}
	}


	/**
	 * 根据Id获取详情
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionGetAssembleConfigById(){
		$id = Yii::$app->request->get('id');
		return $this->service->getProductAssembleConfigById($id);
	}
	/**
	 * 根据Id更新
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionUpdateAssembleConfigById(){

		$model = new ProductAssembleConfigModel(['scenario'=>'update']);
		$model->setAttributes($this->getRequestPost(false)) ;
		if ($model->validate()) {
			return $this->service->updateProductAssembleConfigById($model);
		} else {
		  throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($model));
	   }
	}

	/**
	 * 根据Id删除
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionDelProductAssembleConfig(){

		$id = Yii::$app->request->get('id');
		return $this->service->deleteProductAssembleById($id);
		}

    /*********************ProductAssemble模块控制层************************************/
    /**
     * 添加
     * @return mixed
     * @throws HttpException
     */
    public function actionAddProductAssemble()
    {
        $model = new ProductAssembleModel();
        $model->setAttributes($this->getRequestPost()) ;
        if ($model->validate()) {
            return $this->service->addProductAssemble($model);
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($model));
        }
    }
    /**
     * 分页获取列表
     * @return mixed
     * @throws HttpException
     */
    public function actionGetProductAssemblePage()
    {
        $query = new ProductAssembleQuery();
        $query->setAttributes($this->getRequestGet());
        if ( $query->validate()) {
            return $this->service->getProductAssemblePage( $query);
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($query));
        }
    }


    /**
     * 根据Id获取详情
     * @return mixed
     * @throws HttpException
     */
    public function actionGetProductAssembleById(){
        $id = Yii::$app->request->get('id');
        return $this->service->getProductAssembleById($id);
    }
    /**
     * 根据Id更新
     * @return mixed
     * @throws HttpException
     */
    public function actionUpdateProductAssemble(){

        $model = new ProductAssembleModel(['scenario'=>'update']);
        $model->setAttributes($this->getRequestPost(false)) ;
        if ($model->validate()) {
            return $this->service->updateProductAssembleById($model);
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($model));
        }
    }

    /**
     * 根据Id删除
     * @return mixed
     * @throws HttpException
     */
    public function actionDelProductAssemble(){

        $id = Yii::$app->request->get('id');
        return $this->service->deleteProductAssembleById($id);
    }

	}
/**********************End Of ProductAssembleConfig 控制层************************************/ 



<?php
namespace api\modules\seller\controllers;

use api\modules\auth\ApiAuth;
use api\modules\seller\models\forms\InformationCategoryModel;
use api\modules\seller\models\forms\InformationCategoryQuery;
use api\modules\seller\service\InformationService;
use api\modules\seller\models\forms\InformationModel;
use api\modules\seller\models\forms\InformationQuery;
use fanyou\error\FanYouHttpException;
use fanyou\enums\HttpErrorEnum;
use Yii;
use yii\web\HttpException;

/**
 * Information
 * @author E-mail: Administrator@qq.com
 *
 */
class InformationController extends BaseController
{

    private $service; 
    public function init()
    {
        parent::init();
        $this->service = new InformationService();
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => ApiAuth::class,
        ];
        return $behaviors;
    }
/*********************Information模块控制层************************************/

    /**
     * 添加
     * @return mixed
     * @throws HttpException
     */
    public function actionAddCategory()
    {
        $model = new InformationCategoryModel();
        $model->setAttributes($this->getRequestPost()) ;
        if ($model->validate()) {
            return $this->service->addInformationCategory($model);
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($model));
        }
    }
    /**
     * 获取分类列表
     * @return mixed
     * @throws HttpException
     */
    public function actionGetCategoryList()
    {
        $query = new InformationCategoryQuery();
        $query->setAttributes($this->getRequestGet());
        if ( $query->validate()) {
            return $this->service->getInformationCategoryList( $query);
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($query));
        }
    }

    /**
     * 根据Id获取详情
     * @return mixed
     * @throws HttpException
     */
    public function actionGetCategoryById(){

        return $this->service->getOneCategoryById(parent::getRequestId());
    }
    /**
     * 根据Id更新
     * @return mixed
     * @throws HttpException
     */
    public function actionUpdateCategoryById(){

        $model = new InformationCategoryModel(['scenario'=>'update']);
        $model->setAttributes($this->getRequestPost(false)) ;
        if ($model->validate()) {
            return $this->service->updateInformationCategoryById($model);
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($model));
        }
    }

    /**
     * 根据Id删除分类
     * @return mixed
     * @throws FanYouHttpException
     */
    public function actionDelCategoryById(){
        $id=parent::getRequestId();
        $query = new InformationCategoryQuery();
        $query->pid=$id;
        $count=$this->service->countCategory($query);
        if($count){
            throw  new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,'存在子分类，不能删除');
        }
        return $this->service->deleteCategoryById($id);
    }



    /**
	 * 添加
     * @return mixed
     * @throws HttpException
     */
	public function actionAddInformation()
	{
		$model = new InformationModel();
		$model->setAttributes($this->getRequestPost()) ;
		if ($model->validate()) {
			return $this->service->addInformation($model);
		} else {
			throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($model));
		}
	}
	/**
	 * 分页获取列表
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionGetInformationPage()
	{
		$query = new InformationQuery();
		$query->setAttributes($this->getRequestGet());
		if ( $query->validate()) {
			return $this->service->getInformationPage( $query);
		} else {
			throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($query));
		}
	}


	/**
	 * 根据Id获取详情
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionGetInformationById(){
		$id = Yii::$app->request->get('id');
		return $this->service->getOneById($id);
	}
	/**
	 * 根据Id更新
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionUpdateInformationById(){

		$model = new InformationModel(['scenario'=>'update']);
		$model->setAttributes($this->getRequestPost(false)) ;
		if ($model->validate()) {
			return $this->service->updateInformationById($model);
		} else {
			throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($model));
	   }
	}

	/**
	 * 根据Id删除
	 * @return mixed
	 */
	public function actionDelInformationById(){

		return $this->service->deleteById(parent::getRequestId());
		}
	}
/**********************End Of Information 控制层************************************/ 



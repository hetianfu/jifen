<?php
namespace api\modules\seller\controllers\common;

use api\modules\auth\ApiAuth;
use api\modules\seller\controllers\BaseController;
use api\modules\seller\models\forms\SystemGroupDataModel;
use api\modules\seller\models\forms\SystemGroupDataQuery;
use api\modules\seller\models\forms\SystemGroupModel;
use api\modules\seller\models\forms\SystemGroupQuery;
use api\modules\seller\service\common\SystemGroupService;
use api\modules\seller\service\ProductService;
use fanyou\enums\HttpErrorEnum;
use fanyou\error\FanYouHttpException;
use fanyou\service\FanYouSystemGroupService;
use yii\web\HttpException;

/**
 * SystemGroup
 * @author E-mail: Administrator@qq.com
 *
 */
class SystemGroupController extends BaseController
{

    private $service;
    private $productService;
    public function init()
    {
        parent::init();
        $this->service = new SystemGroupService();
        $this->productService = new ProductService();
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => ApiAuth::class,
            'optional'=>['test']
        ];
        return $behaviors;
    }
/*********************SystemGroup模块控制层************************************/ 
	/**
	 * 添加组合数据
     * @return mixed
     * @throws HttpException
     */
	public function actionAddSystemGroup()
	{
		$model = new SystemGroupModel();
		$model->setAttributes($this->getRequestPost()) ;
		if ($model->validate()) {
       	return $this->service->addSystemGroup($model);
		} else {
		   throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($model));
		}
	}
	/**
	 * 分页获取组合数据
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionGetSystemGroupPage()
	{
		$query = new SystemGroupQuery();
		$query->setAttributes($this->getRequestGet());
		if ( $query->validate()) {

			return $this->service->getSystemGroupPage( $query);
		} else {
		   throw new  FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($query));
		}
	}


	/**
	 * 根据Id获取组合数据详情
	 * @return mixed
	 */
	public function actionGetSystemGroupById(){

		return $this->service->getSystemGroupById( parent::getRequestId());
	}
	/**
	 * 根据Id更新组合数据
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionUpdateSystemGroupById(){

		$model = new SystemGroupModel(['scenario'=>'update']);
		$model->setAttributes($this->getRequestPost(false)) ;
		if ($model->validate()) {
			return $this->service->updateSystemGroupById($model);
		} else {
		  throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($model));
	   }
	}

	/**
	 * 根据Id删除组合数据
	 * @return mixed
	 */
	public function actionDelSystemGroupById(){

		return $this->service->deleteById(parent::getRequestId());
		}

    /**
     * 添加组合数据内容
     * @return mixed
     * @throws HttpException
     */
    public function actionAddSystemGroupData()
    {
        $model = new SystemGroupDataModel();
        $model->setAttributes($this->getRequestPost(true,false)) ;
        if ($model->validate()) {
            return $this->service->addSystemGroupData($model);
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($model));
        }
    }
      /**
     * 分页获取组合数据内容
     * @return mixed
     * @throws HttpException
     */
    public function actionGetNormalGroupDataPage()
    {
        $query = new SystemGroupDataQuery();
        $query->setAttributes($this->getRequestGet());
        $gid=$query->gid;
        $groupInfo=$this->service->getOneById($gid);
        if(empty($groupInfo)){
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($query));
        }
        return $this->service->getSystemGroupDataPage( $query);

    }
    /**
     * 分页商品类型组合数据内容
     * @return mixed
     * @throws HttpException
     */
    public function actionGetProductGroupDataPage()
    {
        $groupInfo=$this->service->getOneById(parent::getRequestId('gid'));

        return FanYouSystemGroupService::getSystemGroupDate($groupInfo,true) ;
    }
    /**
     * 分页活动类型组合数据内容
     * @return mixed
     */
    public function actionGetStrategyGroupDataPage()
    {
        $groupInfo=$this->service->getOneById(parent::getRequestId('gid'));

        return FanYouSystemGroupService::getSystemGroupDate($groupInfo,true) ;
    }
    /**
     * 根据Id获取详情
     * @return mixed
     */
    public function actionGetSystemGroupDataById(){

        return $this->service->getSystemGroupDataById(parent::getRequestId());
    }
    /**
     * 根据Id更新
     * @return mixed
     * @throws HttpException
     */
    public function actionUpdateSystemGroupDataById(){

        $model = new SystemGroupDataModel(['scenario'=>'update']);
        $model->setAttributes($this->getRequestPost(false)) ;
        if ($model->validate()) {

            return $this->service->updateSystemGroupDataById($model);
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($model));
        }
    }

    /**
     * 根据Id删除
     * @return mixed
     */
    public function actionDelSystemGroupDataById(){

        return $this->service->deleteSystemGroupDataById(parent::getRequestId());
    }



	}
/**********************End Of SystemGroup 控制层************************************/ 



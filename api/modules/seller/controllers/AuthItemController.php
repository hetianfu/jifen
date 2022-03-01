<?php
namespace api\modules\seller\controllers;

use api\modules\auth\ApiAuth;
use api\modules\seller\models\forms\AuthItemModel;
use api\modules\seller\models\forms\AuthItemQuery;
use api\modules\seller\service\AuthItemChildService;
use api\modules\seller\service\AuthItemService;
use fanyou\enums\entity\AuthItemEnum;
use fanyou\enums\HttpErrorEnum;
use fanyou\enums\NumberEnum;
use fanyou\enums\QueryEnum;
use fanyou\enums\StatusEnum;
use fanyou\error\FanYouHttpException;
use Yii;
use yii\web\HttpException;

/**
 * AuthItem
 * @author E-mail: Administrator@qq.com
 *
 */
class AuthItemController extends BaseController
{

    private $service;
    private $childService;
    public function init()
    {
        parent::init();
        $this->service = new AuthItemService();
        $this->childService = new AuthItemChildService();
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => ApiAuth::class,
            'optional'=>['get-all','get-currency-menu-item']
        ];
        return $behaviors;
    }
/*********************AuthItem模块控制层************************************/ 
	/**
	 * 添加
     * @return mixed
     * @throws HttpException
     */
	public function actionAddAuthItem()
	{

		$model = new AuthItemModel();
		$model->setAttributes($this->getRequestPost()) ;
        $model->merchant_id=1;
		if ($model->validate()) {
			return $this->service->addAuthItem($model);
		} else {
		   throw new   FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($model));
		}
	}
	/**
	 * 分页获取列表
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionGetAuthItemPage()
	{
		$query = new AuthItemQuery();
		$query->setAttributes($this->getRequestGet(false));
		if ( $query->validate()) {
            $query->limit=1000;
			return $this->service->getAuthItemPage( $query);
		} else {
		   throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($query));
		}
	}

    /**
     * 获取所有菜单列表
     * @return mixed
     * @throws FanYouHttpException
     */
    public function actionGetAuthItemList()
    {
        $query = new AuthItemQuery();
        $query->setAttributes($this->getRequestGet());
        if ( $query->validate()) {

            return $this->service->getAuthItemList( $query);
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($query));
        }
    }
	/**
	 * 根据Id获取详情
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionGetAuthItemById(){
		$id = Yii::$app->request->get('id');
		return $this->service->getAuthItemById($id);
	}
	/**
	 * 根据Id更新
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionUpdateAuthItem(){

		$model = new AuthItemModel(['scenario'=>'update']);
		$model->setAttributes($this->getRequestPost(false)) ;
		if ($model->validate()) {
			return $this->service->updateAuthItemById($model);
		} else {
		  throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($model));
	   }
	}

	/**
	 * 根据Id删除
	 * @return mixed
	 */
	public function actionDelAuthItem(){
		return $this->service->deleteById(parent::getRequestId());
	}
    /**
     * 设置展示权限菜单树
     * 获取所选角色的权限Id
     * @return mixed
     */
    public function actionGetAuthItemByRoles(){
        $roleIds = Yii::$app->request->get(AuthItemEnum::ROLELIST);
        $itemIds=$this->childService->findItemByRoleIds(explode(',',$roleIds));
        return $itemIds ;
    }

    /**
     * 获取当前权限所有菜单栏目列表
     * @return mixed
     */
    public function actionGetCurrencyMenuItem(){

        if(parent::isAdmin()){
            $query=new AuthItemQuery();
            $query->merchant_id=parent::getMerchantId();
            $query->pid=StatusEnum::STATUS_INIT;
            return  $this->service->recursion($query,[], NumberEnum::ONE);
        }
        return $this->service->getCurrencyMenuItem(  parent::getRoleIds() );

    }

    /**
     * 获取登陆者所有权限
     * @return mixed
     */
//    public function actionGetAll(){
//        return $this->service->getAuthItemByRoles(  parent::getRoleIds()  );
//
//    }


}
/**********************End Of AuthItem 控制层************************************/ 



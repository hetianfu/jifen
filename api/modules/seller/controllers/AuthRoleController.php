<?php
namespace api\modules\seller\controllers;

use api\modules\auth\ApiAuth;
use api\modules\seller\models\forms\AuthItemQuery;
use api\modules\seller\models\forms\AuthRoleModel;
use api\modules\seller\models\forms\AuthRoleQuery;
use api\modules\seller\service\AuthItemChildService;
use api\modules\seller\service\AuthItemService;
use api\modules\seller\service\AuthRoleService;
use fanyou\components\casbin\event\CasbinEvent;
use fanyou\enums\entity\AuthItemEnum;
use fanyou\enums\HttpErrorEnum;
use fanyou\enums\QueryEnum;
use fanyou\error\FanYouHttpException;
use Yii;
use yii\web\HttpException;

/**
 * AuthRole
 * @author E-mail: Administrator@qq.com
 *
 */
class AuthRoleController extends BaseController
{

    private $service;
    private $itemService;
    private $childService;

    const EVENT_ADD_ROLE = 'add_role';
    const EVENT_DEL_ROLE = 'del_role';
    public function init()
    {
        parent::init();
        $this->service = new AuthRoleService();
        $this->childService = new AuthItemChildService();
        $this->itemService = new AuthItemService();

        //定义订阅事件-发放优惠券,减库存 ，修改积分，发送订阅消息,用户购买记录，打印订单
        $this->on(self::EVENT_ADD_ROLE, ['fanyou\components\casbin\event\CasbinEventService', 'update']);
        $this->on(self::EVENT_DEL_ROLE, ['fanyou\components\casbin\event\CasbinEventService', 'del']);
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => ApiAuth::class,
        ];
        return $behaviors;
    }
/*********************AuthRole模块控制层************************************/ 
	/**
	 * 添加
     * @return mixed
     * @throws HttpException
     */
	public function actionAddAuthRole()
	{
		$model = new AuthRoleModel();
		$model->setAttributes($this->getRequestPost()) ;
		if ($model->validate()) {
            return $this->service->addAuthRole($model);;
		} else {
		   throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($model));
		}
	}
	/**
	 * 分页获取列表
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionGetAuthRolePage()
	{
		$query = new AuthRoleQuery();
		$query->setAttributes($this->getRequestGet());
		if ( $query->validate()) {
			return $this->service->getAuthRolePage( $query);
		} else {
		   throw new  FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($query));
		}
	}
    public function actionGetAuthRoleList()
    {
        $query = new AuthRoleQuery();
        $query->setAttributes($this->getRequestGet());
        if ( $query->validate()) {
            return $this->service->getAuthRoleList( $query);
        } else {
            throw new  FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($query));
        }
    }

	/**
	 * 根据Id获取详情
	 * @return mixed
	 */
	public function actionGetAuthRoleById(){

		$id = Yii::$app->request->get('id');
		return $this->service->getOneById($id);
	}
	/**
	 * 根据Id更新
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionUpdateAuthRole(){

		$model = new AuthRoleModel(['scenario'=>'update']);
		$model->setAttributes($this->getRequestPost(false)) ;
		if ($model->validate()) {
            return $this->service->updateAuthRoleById($model);
		} else {
		  throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($model));
	   }
	}

	/**
	 * 根据Id删除
	 * @return mixed
	 */
	public function actionDelAuthRole(){
		$id = Yii::$app->request->get('id');

		$result=$this->service->deleteById($id);
		if($result){
            $event=new CasbinEvent();
            $event->roleId=$id;
		    $this->trigger(self::EVENT_DEL_ROLE,$event);
        }
		return $result;
		}

    /**
     * 为角色授权
     * @return mixed
     */
    public function actionAssignAuthRole(){
        $params = Yii::$app->request->post(AuthItemEnum::ITEMLIST);
        $extendItem= Yii::$app->request->post(AuthItemEnum::EXTEND_ITEM);
        $roleId=Yii::$app->request->post('id');
        if(isset($extendItem)){
            $updateModel=new AuthRoleModel();
            $updateModel->id=$roleId;
            $updateModel->extend_item= json_encode($extendItem);
            $this->service->updateAuthRoleById($updateModel);
        }
        $count=$this->childService->accreditByDefault($roleId,$params);
        if($count){

           if( is_array($params)){
              $query=new  AuthItemQuery();
              $itemIds= implode(',',array_column($params,'id')) ;

              $query->id=QueryEnum::IN.$itemIds;
              $event=new CasbinEvent();
              $event->items= $this->itemService->getCasbinItemList($query);
              $event->roleId=$roleId;

              $this->trigger(self::EVENT_ADD_ROLE,$event);
           }
        }
        return $count;
        }
	}
/**********************End Of AuthRole 控制层************************************/ 



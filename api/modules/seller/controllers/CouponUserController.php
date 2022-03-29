<?php
namespace api\modules\seller\controllers;

use api\modules\auth\ApiAuth;
use api\modules\seller\service\CouponService;
use api\modules\seller\service\CouponUserService;
use api\modules\seller\models\forms\CouponUserModel;
use api\modules\seller\models\forms\CouponUserQuery;

use fanyou\enums\AppEnum;
use fanyou\enums\HttpErrorEnum;
use fanyou\enums\StatusEnum;
use fanyou\error\errorCode\ErrorUser;
use fanyou\error\FanYouHttpException;
use fanyou\tools\DaysTimeHelper;
use Yii;
use yii\web\HttpException;

/**
 * CouponUser
 * @author E-mail: Administrator@qq.com
 *
 */
class CouponUserController extends BaseController
{

    private $service;
    private $couponService;
    public function init()
    {
        parent::init();
        $this->service = new CouponUserService();
        $this->couponService = new CouponService();
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => ApiAuth::class,
        ];
        return $behaviors;
    }
/*********************CouponUser模块控制层************************************/ 
	/**
	 * 添加
     * @return mixed
     * @throws HttpException
     */
	public function actionSendToUser()
	{

            $couponId=parent::postRequestId('couponId');
		    $userIds=parent::postRequestId('userIds');
            $coupon=$this->couponService->getOneById($couponId);

		    if(empty($userIds)){
                throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,ErrorUser::USER_UN_LEGAL);
            }
		    $result=0;
            $now=time();
		    foreach ($userIds as $k=>$v){
                $model = new CouponUserModel();
                $model->setAttributes($this->couponService->getOneById($couponId),false) ;
                $model->status=StatusEnum::STATUS_INIT;
                $model->merchant_id=parent::getMerchantId();
                $model->editor=parent::getAccountId();
                $model->title= $coupon['template']['title'];
                //print_r($coupon['template']['title']);exit;
                $model->amount=floatval($coupon['template']['amount']);
                $model->limit_amount=floatval( $coupon['template']['limit_amount']);
                $model->type= $coupon['template']['type'];
                $model->type_relation_id= $coupon['template']['type_relation_id'];
                $model->get_method= AppEnum::SELLER;
              //  print_r( ($coupon['template']['effect_days']));exit;
                $model->fromtime=$now;
                $model->totime=$now+intval($coupon['template']['effect_days'])*DaysTimeHelper::ONE_DAY;
                $model->coupon_id= $couponId;
                $model->user_id=$v;
                $result+=  empty($this->service->addCouponUser($model))?0:1;
            }
            return count($userIds);
	}
	/**
	 * 分页获取列表
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionGetCouponUserPage()
	{
		$query = new CouponUserQuery();
		$query->setAttributes($this->getRequestGet(false));
		if ( $query->validate()) {
			return $this->service->getCouponUserPage( $query);
		} else {
		   throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($query));
		}
	}


	/**
	 * 根据Id获取详情
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionGetCouponUserById(){
		$id = Yii::$app->request->get('id');
		return $this->service->getCouponUserById($id);
	}
	/**
	 * 根据Id更新
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionUpdateCouponUser(){

		$model = new CouponUserModel(['scenario'=>'update']);
		$model->setAttributes($this->getRequestPost(false)) ;
		if ($model->validate()) {
			return $this->service->updateCouponUserById($model);
		} else {
		  throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($model));
	   }
	}

	/**
	 * 根据Id删除
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionDelCouponUser(){

		$id = Yii::$app->request->get('id');
		return $this->service->deleteById($id);
		}
	}
/**********************End Of CouponUser 控制层************************************/ 



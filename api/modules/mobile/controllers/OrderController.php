<?php
namespace api\modules\mobile\controllers;

use api\modules\auth\ApiAuth;
use api\modules\mobile\models\event\TaskEvent;
use api\modules\mobile\models\forms\BasicOrderInfoModel;
use api\modules\mobile\models\forms\BasicOrderInfoQuery;
use api\modules\mobile\models\forms\RefundOrderApplyModel;
use api\modules\mobile\models\forms\UserInfoModel;
use api\modules\mobile\service\BasicOrderInfoService;
use api\modules\mobile\service\WxPayService;
use fanyou\enums\entity\OrderStatusEnum;
use fanyou\enums\HttpErrorEnum;
use fanyou\enums\OrderPayEnum;
use fanyou\error\errorCode\ErrorOrder;
use fanyou\error\FanYouHttpException;
use fanyou\service\WuLiuService;
use fanyou\tools\helpers\Url;
use fanyou\tools\QrcodeHelper;
use fanyou\tools\StringHelper;
use Yii;
use yii\db\Expression;
use yii\web\HttpException;

/**
 * Class OrderController
 * @package api\modules\mobile\controllers
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-03-17 10:06
 */
class OrderController extends BaseController
{   const EVENT_CANCEL_ORDER= 'cancel_order';
    const resultEmpty='';
    private $service;
    private $wxPayService;

    public function init()
    {
        parent::init();
        $this->service = new BasicOrderInfoService();
        $this->wxPayService = new WxPayService();
        $this->on(self::EVENT_CANCEL_ORDER, ['api\modules\mobile\service\event\StockEventService', 'rollProductStockTask']);
        $this->on(self::EVENT_CANCEL_ORDER, ['api\modules\mobile\service\event\CouponEventService', 'rollUserCouponTask']);
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => ApiAuth::class,
             'optional'=>['get-connect-user','query-kdi']
        ];
        return $behaviors;
    }
/*********************BasicOrderInfo模块控制层************************************/

    /**
     * 查询快递
     * @return array|mixed|object|string
     * @throws FanYouHttpException
     */
    public function actionQueryKdi()
    {
        return WuLiuService::queryKdi(parent::getRequestId("expressNo"));
    }
    /**
     * 获取联系人
     * @return mixed|string
     */
    public function actionGetConnectUser(){
        $userId=parent::getUserId();
        $orderInfo=$this->service->getLastOneByUserId($userId);

        return is_null($orderInfo['connect_snap'])?$this::resultEmpty:json_decode($orderInfo['connect_snap']);
    }
    /**
     * 获取用户最后一个快递订单的收货地址
     * @return mixed|string
     */
    public function actionGetFreightAddress(){

        $orderInfo=$this->service->getLastOneByUserId(parent::getUserId());
        return is_null($orderInfo['address_snap'])?null:json_decode($orderInfo['connect_snap']);
    }
	/**
	 * 分页获取列表
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionGetPage()
	{
		$query = new BasicOrderInfoQuery();
        $searchWord=Yii::$app->request->get("addressSnap");

		if( empty($searchWord)){
            $userCache = Yii::$app->user->identity;
            $query->userId = (string)$userCache['id'];

		    $query->setAttributes($this->getRequestGet());
        }else{
            $query->setAttributes($this->getRequestGet(false));
        }
		if ( $query->validate()) {
			return $this->service->getBasicOrderInfoPage( $query);
		} else {
		   throw new  FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($query));
		}
	}

	/**
	 * 根据Id获取详情
	 * @return mixed
	 */
	public function actionGetById(){

        $orderInfo=$this->service->getOneById(parent::getRequestId());
        if($orderInfo['user_id']!==parent::getUserId()){
            $orderInfo['is_proxy_pay']=1;
        }
        return $orderInfo;
	}

    /**
     *  获取二维码
     * @return string
     * @throws FanYouHttpException
     */
    public function actionGetQrCode(){
       $orderInfo=$this->service->getOneById(parent::getRequestId());
       return  QrcodeHelper::createQrCode(Url::toOAuth2().'?barCode='. $orderInfo['show_bar_qrcode']);
    }

	/**
	 *  确认收货
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionReceiveById(){

		$model = new BasicOrderInfoModel(['scenario'=>'update']);
		$model->setAttributes($this->getRequestPost(false)) ;
		if ($model->validate()) {
            $model->status=OrderStatusEnum::CLOSED;
			return $this->service->updateBasicOrderInfoById($model);
		} else {
		  throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($model));
	   }
	}

	/**
	 * 根据Id删除
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionDelById(){

		return $this->service->deleteById(parent::getRequestId());
		}


    /**
     * 取消订单
     * @return mixed
     * @throws HttpException
     */
    public function actionCancelById(){
        $id=parent::getRequestId();
        //取消订单，回滚库存，返还优惠券
        $model= BasicOrderInfoModel::find()->select(['id','status','user_id','cart_snap','merchant_id','user_coupon_id','deduct_score'])
            ->where(['id'=>$id])
            ->asArray()->one();
        if($model['status']!==OrderStatusEnum::UNPAID){
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,'订单不能取消');
        }
        $result=$this->service->cancelOrderById($id);
        if($result){
        $event=new TaskEvent();
        $event->orderList=[$model];

        $this->trigger(self::EVENT_CANCEL_ORDER ,$event);
        parent::deleteCache(OrderPayEnum::WX_JS_SDK . $id);
        //解锁积分
        UserInfoModel::updateAll(['lock_score' => new Expression('`lock_score` -' . $model['deduct_score'])], ['id' => $model['user_id']]);



        }
        return  $result;

    }

    /**
     * 退单
     * @return mixed
     * @throws FanYouHttpException
     */
    public function actionRefundById(){

        $apply=new  RefundOrderApplyModel();
        $apply->setAttributes(parent::getRequestPost(),false);
        $apply->refund_id=StringHelper::uuid();
        //添加一条审批列表
        $result=$this->service->refundOrderById($apply);
        if(!$result){
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,ErrorOrder::ORDER_CAN_NOT_REFUND);
        }
        return  $result;

    }


}
/**********************End Of BasicOrderInfo 控制层************************************/ 



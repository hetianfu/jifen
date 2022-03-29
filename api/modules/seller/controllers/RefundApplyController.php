<?php
namespace api\modules\seller\controllers;

use api\modules\auth\ApiAuth;
use api\modules\seller\models\event\OrderEvent;
use api\modules\seller\models\forms\RefundOrderApplyModel;
use api\modules\seller\models\forms\RefundOrderApplyQuery;
use api\modules\seller\service\RefundApplyService;
use api\modules\seller\service\wechat\WxPayService;
use fanyou\enums\HttpErrorEnum;
use fanyou\enums\StatusEnum;
use fanyou\error\FanYouHttpException;
use fanyou\tools\StringHelper;
use yii\web\HttpException;

/**
 * RefundOrderApply
 * @author E-mail: Administrator@qq.com
 *
 */
class RefundApplyController extends BaseController
{

    private $service;
    private $wxPayService;

    const EVENT_ORDER_REFUND= 'refund_order';
    public function init()
    {
        parent::init();
        $this->service = new RefundApplyService();
        $this->wxPayService=new WxPayService();
        //定义订阅事件-核销订单
//        $this->on(self::EVENT_ORDER_REFUND, ['api\modules\seller\service\event\RefundDistributeEventService', 'distribute']);
//
//        $this->on(self::EVENT_ORDER_REFUND, ['api\modules\seller\service\event\RefundScoreEventService', 'refundScoreDetail']);
        $this->on(self::EVENT_ORDER_REFUND, ['api\modules\seller\service\event\MessageEventService', 'refundOrder']);


    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => ApiAuth::class,
            'optional'=>['sum']
        ];
        return $behaviors;
    }
/*********************RefundOrderApply模块控制层************************************/

    /**
     * 统计数据
     * @return mixed
     * @throws HttpException
     */
    public function actionSum()
    {
        $query = new RefundOrderApplyQuery();
        $query->setAttributes($this->getRequestGet());
        if ($query->validate()) {
            $query->status=null;
            return $this->service->sum($query);
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, parent::getModelError($query));
        }
    }

	/**
	 * 分页获取列表
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionGetPage()
	{
		$query = new RefundOrderApplyQuery();
		$query->setAttributes($this->getRequestGet());
		if ( $query->validate()) {
            $result=$this->service->getRefundOrderApplyPage( $query);
            $query->status=null;
            $result['header']= $this->service->sum($query);
			return $result;
		} else {
		   throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($query));
		}
	}
    /**
     * 测试
     * @return mixed
     */
    public function actionTestRefundById(){

        $model=new  RefundOrderApplyModel();
        $model->id=StringHelper::randomNum();
        $model->order_amount=2;
        $model->refund_amount=1;
        return  $this->wxPayService->refundPayOrder($model);
    }

	/**
	 * 根据Id获取详情
	 * @return mixed
	 */
	public function actionGetRefundById(){
		return $this->service->getRefundById(parent::getRequestId());
	}
	/**
	 * 审批不通过
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionForbid(){

		$model = new RefundOrderApplyModel(['scenario'=>'update']);
		$model->setAttributes($this->getRequestPost(false)) ;
		if ($model->validate()) {
            $model->status=StatusEnum::FORBID;
			return $this->service->forbidRefundOrder($model);
		} else {
		  throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($model));
	   }
	}
    /**
     * 审批通过
     * @return mixed
     * @throws HttpException
     */
    public function actionApprove(){
        $request=$this->getRequestPost(false);
        //添加一条审批列表
        $verifyModel = new RefundOrderApplyModel();
        $verifyModel->setAttributes($request) ;
        $verifyModel->refund_id=StringHelper::uuid();

        $result=$this->service->forceToRefundOrderById($verifyModel);
        if(!$result) {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($verifyModel));
        }
        $model = new RefundOrderApplyModel(['scenario'=>'update']);
        $model->setAttributes($request) ;
        if ($model->validate()) {
            $model->status=StatusEnum::APPROVE;
            $result=$this->service->approveRefundById($model);

            if($result){
                $event=new OrderEvent();
                $event->id= $model->id ;
                $this->trigger(self::EVENT_ORDER_REFUND,$event);
            }
            return  $result;
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($model));
        }
    }
	/**
	 * 根据查单条退单详情
	 * @return mixed
	 */
	public function actionGetOneById(){

		return $this->service->getOneById(parent::getRequestId());
	    }
	}
/**********************End Of RefundOrderApply 控制层************************************/ 



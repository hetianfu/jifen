<?php
namespace api\modules\mobile\controllers;

use api\modules\auth\ApiAuth;
use api\modules\mobile\models\event\TaskEvent;
use api\modules\mobile\models\forms\BasicOrderInfoModel;
use api\modules\mobile\models\forms\CouponModel;
use api\modules\mobile\models\forms\CouponUserModel;
use api\modules\mobile\service\CategoryService;
use fanyou\enums\entity\OrderStatusEnum;
use fanyou\enums\HttpErrorEnum;
use fanyou\enums\NumberEnum;
use fanyou\enums\StatusEnum;
use fanyou\error\FanYouHttpException;
use fanyou\tools\DaysTimeHelper;
use Yii;

class TaskController extends BaseController
{

    private $service;
    const EVENT_CANCEL_ORDER= 'cancel_order';

    public function init()
    {
        parent::init();
        $this->service = new CategoryService();
        $this->on(self::EVENT_CANCEL_ORDER, ['api\modules\mobile\service\event\StockEventService', 'rollProductStockTask']);
        $this->on(self::EVENT_CANCEL_ORDER, ['api\modules\mobile\service\event\CouponEventService', 'rollUserCouponTask']);

    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => ApiAuth::class,
            'optional' => ['cancel-order','seven-days-closed','disable-coupon','disable-user-coupon','test']
        ];
        return $behaviors;
    } 
/*********************Category模块控制层************************************/
    public function actionTest(){
     return parent::getRequestPost();
    }
    /**
     * 取消订单，
     * 回滚库存
     * 回滚优惠券
     */
    public function actionCancelOrder(){
        //取消订单，回滚库存，返还优惠券
        $orderList= BasicOrderInfoModel::find()->select(['id','user_id','cart_snap','merchant_id','user_coupon_id'])
            ->where(['status'=>OrderStatusEnum::UNPAID])
            ->andWhere(['<=','un_paid_seconds',time()])
            ->asArray()->all();
        if(empty($orderList)) {
            return NumberEnum::ZERO;
        }
        BasicOrderInfoModel::updateAll(['status' =>OrderStatusEnum::CANCELLED,'refund_able' =>StatusEnum::DISABLED],
            ['in','id',array_column($orderList,'id')]);

        $event=new TaskEvent();
        $event->orderList=$orderList;
        $this->trigger(self::EVENT_CANCEL_ORDER ,$event);
        return count($orderList);
    }

    /**
     * 七天默认结单
     */
    public function actionSevenDaysClosed(){

        $daysAgo= DaysTimeHelper::daysAgo(NumberEnum::SEVEN,true)['start'];
        return  BasicOrderInfoModel::updateAll(
            ['status'=>OrderStatusEnum::CLOSED,'refund_able' =>StatusEnum::DISABLED]
            ,['and',['status'=>OrderStatusEnum::SENDING],['<=','paid_time',$daysAgo] ] );
    }

    /**
     * 失效优惠券
     */
    public function actionDisableCoupon(){
        $number=CouponModel::updateAll(['status'=>StatusEnum::DISABLED],[  'left_number'=>NumberEnum::ZERO]);
        $number+=CouponModel::updateAll(['status'=>StatusEnum::DISABLED],[ 'and',['is_permanent'=>StatusEnum::DISABLED],['<','totime',time()] ] );
        return  $number;
    }

    /**
     * 失效用户优惠券
     * @return int
     */
    public function actionDisableUserCoupon(){
        $number=CouponUserModel::updateAll(['status'=>StatusEnum::EXPIRE],[ 'and',['status'=>StatusEnum::UN_USED,'is_del'=>StatusEnum::DISABLED,],['<','totime',time()] ]);
        return  $number;
    }

}	
/**********************End Of Category 控制层************************************/ 
 


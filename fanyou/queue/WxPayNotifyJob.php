<?php

namespace fanyou\queue;

use api\modules\mobile\models\forms\BasicOrderInfoModel;
use api\modules\mobile\models\forms\CouponUserModel;
use api\modules\mobile\models\forms\UserProductModel;
use api\modules\mobile\models\forms\UserScoreDetailModel;
use api\modules\mobile\service\CouponService;
use api\modules\mobile\service\ProductService;
use api\modules\mobile\service\UserProductService;
use api\modules\mobile\service\UserScoreDetailService;
use api\modules\mobile\service\WechatMessageService;
use fanyou\enums\StatusEnum;
use Yii;
use yii\base\BaseObject;
use yii\queue\JobInterface;

/**
 * Class WxPayNotifyJob
 * @package fanyou\queue
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-07-10 11:20
 */
class WxPayNotifyJob extends BaseObject implements  JobInterface
{

    private $orderInfo;
    private $userProductService;
    private $productService;
    private $userScoreService;
    private $couponService;
    private $msgService;
    public function __construct(BasicOrderInfoModel $order)
    {
        $this->userProductService =new UserProductService();
        $this->productService =new ProductService();
        $this->userScoreService = new UserScoreDetailService();
        $this->msgService=new WechatMessageService();
        $this->couponService=new CouponService();
        $this->orderInfo=$order;
    }

    /**
     * @param \yii\queue\Queue $queue
     * @return mixed|void
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function execute($queue)
    {   $userCouponId=$this->orderInfo->user_coupon_id;
        $userId=$this->orderInfo->user_id;
        CouponUserModel::updateAll(['status'=>StatusEnum::USED],['id'=>$userCouponId]);
        $score=$this->orderInfo->product_score-$this->orderInfo->deduct_score;
        $userScore=new UserScoreDetailModel();
        $userScore->user_id=$userId;
        if($score<0){
            $userScore->is_deduct=StatusEnum::COME_OUT;
            $score *=$userScore->is_deduct;
        }else{
            $userScore->is_deduct=StatusEnum::COME_IN;
        }
        $userScore->order_id=$this->orderInfo->id;
        $userScore->score=$score;

       // $this->userScoreService->addUserScoreDetail($userScore);

        $stockList= json_decode($this->orderInfo->cart_snap);
        $userProductList=[];
        foreach ($stockList as $k=>$value){
 //          $stock=new StockOrder();
//            $stock->setAttributes(ArrayHelper::toArray($value),false);
//            $this->productService ->minusSkuNumberById($stock->id,$stock->number);
            $userProduct=new UserProductModel();
            $userProduct->user_id=$userId;
            $userProduct->product_id=$value['id'];
            $userProduct->product_name=$value['name'];
            $userProduct->number=$value['number'];
            $userProduct->status=StatusEnum::ENABLED;

            $userProductList[]=$userProduct;
        }
        $this->userProductService->batchAddUserProduct($userProductList);


        $this->sendOrderPayMessage($this->orderInfo->id);

        //新建一个组合job，处理，库存，用户积分，统计报表  等 等

        //用户积分，用户分销 --事件，

        $job=new UserDistributeJob();
        $job->userId=$userId;
        $job->id=$this->orderInfo->id;
        $job->amount=$this->orderInfo->pay_amount;
        $id = Yii::$app->queue->push($job);


        //$order->update();

    }
    private  function  sendOrderPayMessage($id){
//        $templateId="";
//        $userInfo=Yii::$app->user->identity;
//        $openid=$userInfo['miniAppOpenId'];
//
//        $data=[];
//        $data=$userInfo['name'];
//        $data=$this->orderInfo->pay_amount;
        $this->msgService->sendPayMsg($id);
    }

}
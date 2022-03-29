<?php

namespace api\modules\mobile\service\event;

use api\modules\mobile\models\event\OrderPayEvent;
use api\modules\mobile\models\event\TaskEvent;
use api\modules\mobile\models\forms\BasicOrderInfoModel;
use api\modules\mobile\models\forms\ProductSkuModel;
use api\modules\mobile\service\BasicService;
use api\modules\seller\models\forms\ProductModel;
use api\modules\seller\models\forms\ProductStockDetailModel;
use fanyou\common\StockOrder;
use fanyou\enums\entity\StockStatusEnum;
use fanyou\enums\HttpErrorEnum;
use fanyou\enums\StatusEnum;
use fanyou\error\errorCode\ErrorProduct;
use fanyou\error\FanYouHttpException;
use fanyou\tools\ArrayHelper;
use yii\db\Expression;


/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-03-23
 */
class StockEventService  extends BasicService
{
    /*********************Product模块服务层************************************/
    /**
     * 订单支付，减库存
     * @param OrderPayEvent $event
     * @throws \Throwable
     */
    public static  function minusSkuNumberById(OrderPayEvent  $event)
    {  //如果减库存失败，则抛出异常
        $order=$event->orderInfo;
        $userId=$order->user_id;
        $stockList= json_decode($order->cart_snap) ;
        $successStockList=[];
        foreach ($stockList as $k=>$value){
            $stock=new StockOrder();
            $stock->setAttributes(ArrayHelper::toArray($value),false);
            $id= strval($stock->stockId);

            $number=$stock->number;
            self::addStockDetail($order->id,$userId,$order->merchant_id,$stock);

            $result= ProductSkuModel::updateAll(['stock_number' => new Expression('`stock_number` - ' . $number)], ['and', ['id' => $id], ['>=', 'stock_number', $number]]);
            //商品销量增长
            ProductModel::updateAll(['real_sales_number' => new Expression('`real_sales_number` + ' . $number),'sales_number' => new Expression('`sales_number` + ' . $number)],
                ['id' => $stock->id] );
            if($result){
                $successStockList[]=$stock;
            }else {
              self::reBackProductStock($successStockList);
              throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,ErrorProduct::STOCK_NUN_ENOUGH);
          }
            $arrayValue=ArrayHelper::toArray($value);
            if(isset($arrayValue['isGoodRefund'])){
                if($arrayValue['isGoodRefund']==StatusEnum::DISABLED ){
                    $isRefund=StatusEnum::DISABLED;
                }
            }
        }
        if(isset($isRefund)&&$isRefund==StatusEnum::DISABLED ){ BasicOrderInfoModel::updateAll(['refund_able'=>StatusEnum::DISABLED],['id'=>$order['id']]);}
    }

    /**
     * 定时任务
     * 取消订单，回滚库存
     * @param TaskEvent $event
     * @throws \Throwable
     */
    public static function rollProductStockTask(TaskEvent $event)
    {
        $orderList=$event->orderList;

        foreach ($orderList as $k=>$v){
            $orderId=$v['id'];
            $userId=$v['user_id'];
            $merchantId=$v['merchant_id'];
            $cartSnap=$v['cart_snap'];
            $stockList= json_decode($cartSnap) ;

                foreach ($stockList as $k=>$value){
                    $stock=new StockOrder();
                    $stock->setAttributes(ArrayHelper::toArray($value),false);
                    $id= strval($stock->stockId);
                    $number=$stock->number;
                    self::cancelStockDetail($orderId,$userId,$merchantId,$stock);

                    ProductSkuModel::updateAll(['stock_number' => new Expression('`stock_number` + ' . $number)],  ['id' => $id] );
                    //商品销量回滚
                    ProductModel::updateAll(['real_sales_number' => new Expression('`real_sales_number` -' .$number),'sales_number' => new Expression('`sales_number` -' . $number)],
                        ['id' => $stock->id] );
                    //差一条记录：            print_r($id);exit;

                }
        }

    }

    /**
     * 添加商品销售出库记录
     * @param $orderId
     * @param $userId
     * @param $merchantId
     * @param StockOrder $stock
     * @return bool
     * @throws \Throwable
     */
    private static function addStockDetail($orderId, $userId,$merchantId,StockOrder $stock) {
        $stockDetail=new ProductStockDetailModel();
        $stockDetail->merchant_id=$merchantId;
        $stockDetail->order_id=$orderId;
        $stockDetail->sku_id=$stock->stockId;
        $stockDetail->product_id=$stock->id;
        if($stockDetail->sku_id!=$stockDetail->product_id) {
            $stockDetail->sku_stock=1;
        };
        $stockDetail->cost_amount=$stock->costAmount;
        $stockDetail->sales_amount=$stock->amount;
        $stockDetail->product_name=$stock->name;
        $stockDetail->spec_snap=$stock->specSnap;

        $stockDetail->sub_type=StatusEnum::COME_OUT;
        $stockDetail->type=StockStatusEnum::SALE_OUT;


        $stockDetail->source_path=$stock->strategyType;
        $stockDetail->stock_number=$stock->number*$stockDetail->sub_type;
        $stockDetail->operator=$userId;
        return $stockDetail->insert();
    }

    /**
     * 添加商品退单/取消 退还入库记录
     * @param $orderId
     * @param $userId
     * @param $merchantId
     * @param StockOrder $stock
     * @return bool
     * @throws \Throwable
     */
    private static function cancelStockDetail($orderId,$userId, $merchantId,StockOrder $stock) {
        $stockDetail=new ProductStockDetailModel();
        $stockDetail->merchant_id=$merchantId;
        $stockDetail->order_id=$orderId;
        $stockDetail->sku_id=$stock->stockId;
        $stockDetail->product_id=$stock->id;
        if($stockDetail->sku_id!=$stockDetail->product_id) {
            $stockDetail->sku_stock=1;
        };
        $stockDetail->product_name=$stock->name;
        $stockDetail->spec_snap=$stock->specSnap;
        $stockDetail->sub_type=StatusEnum::COME_IN;
        $stockDetail->type=StockStatusEnum::REFUND_IN;
        $stockDetail->stock_number=$stock->number;
        $stockDetail->source_path=$stock->strategyType;
        $stockDetail->stock_number=$stock->number*$stockDetail->sub_type;
        $stockDetail->operator=$userId;
        return  $stockDetail->insert();

    }

    /**
     * 库存不足回滚
     * 之前添加的部分
     * @param $array
     */
    private static function reBackProductStock($array)
    {
        foreach ($array as $k=>$value){
            $id=$value['id'];
            $stockId=$value['stockId'];
            $number=$value['number'];

            ProductSkuModel::updateAll(['stock_number' => new Expression('`stock_number` + ' .$number)],  ['id' => $stockId] );
            //商品销量回滚
            ProductModel::updateAll(['real_sales_number' => new Expression('`real_sales_number` -' .$number),'sales_number' => new Expression('`sales_number` -' .$number)],
                ['id' => $id] );
        }
    }
}
/**********************End Of Product 服务层************************************/


<?php

namespace api\modules\mobile\service\event;

use api\modules\mobile\models\event\OrderPayEvent;
use api\modules\mobile\models\forms\SaleProductModel;
use api\modules\mobile\service\BasicService;
use api\modules\seller\models\forms\SaleProductLimitModel;
use fanyou\enums\entity\StrategyTypeEnum;
use fanyou\tools\ArrayHelper;
use fanyou\tools\StringHelper;


/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-03-23
 */
class LimitEventService extends BasicService
{
    /*********************Product模块服务层************************************/
    /**
     * 订单秒杀商品限购
     * @param OrderPayEvent $event
     * @throws \Throwable
     */
    public static function buySaleLimit(OrderPayEvent $event)
    {  //如果减库存失败，则抛出异常
        $order = $event->orderInfo;
        if ($order->order_product_type == StrategyTypeEnum::SECKILL) {
            $userId = $order->user_id;
            $stockList = json_decode($order->cart_snap);
            foreach ($stockList as $k => $value) {
                $array = ArrayHelper::toArray($value);
                $saleProduct = SaleProductModel::findOne($array['id']);
                if (!empty($saleProduct)) {
                    $limit = new SaleProductLimitModel();
                    $limit->product_id = $array['id'];
                    $limit->key_id = $limit->product_id . $saleProduct->start_date;
                    $limit->id = StringHelper::uuid();
                    $limit->number = $array['number'];
                    $limit->limit_number = $saleProduct->limit;
                    $limit->user_id = $userId;
                    $limit->insert();
                }
            }
        }
    }

}
/**********************End Of Product 服务层************************************/


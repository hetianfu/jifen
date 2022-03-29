<?php

namespace api\modules\mobile\service\event;

use api\modules\mobile\models\event\OrderPayEvent;
use api\modules\mobile\models\forms\BasicOrderInfoModel;
use api\modules\mobile\models\forms\PinkModel;
use api\modules\mobile\models\forms\PinkPartakeModel;
use fanyou\enums\entity\OrderStatusEnum;
use fanyou\enums\NumberEnum;
use fanyou\enums\StatusEnum;
use fanyou\error\FanYouHttpException;
use fanyou\service\SendWxMsgService;
use fanyou\tools\ArrayHelper;
use yii\db\Expression;


/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-03-23
 */
class PinkEventService
{
    public function __construct()
    {
    }

    /*********************Product模块服务层************************************/
    /**
     * 拼团订单触发事件
     * @param OrderPayEvent $event
     * @throws FanYouHttpException
     * @throws \Throwable
     */
    public static function pinkProduct(OrderPayEvent $event)
    {
        $order = $event->orderInfo;
        $stockList = json_decode($order->cart_snap);
        $stockList = ArrayHelper::toArray($stockList);
        foreach ($stockList as $k => $value) {
            $pinkId = $value['pinkId'];
            PinkModel::updateAll(['is_effect' => NumberEnum::ONE, 'currency_num' => new Expression('`currency_num` + 1')], ['id' => $pinkId]);
            PinkModel::updateAll(['status' => NumberEnum::ONE], ['>=', 'currency_num', new Expression('`total_num`')]);
            //团购下单记录,如果带了团购Id，则提交订单需传入pinkId
            PinkPartakeModel::updateAll(['status' => NumberEnum::ONE],
                ['order_id' => $order->id, 'user_id' => $order->user_id, 'pink_id' => $pinkId]);
            $pink = PinkModel::findOne($pinkId);
            if ($pink->currency_num >= $pink->total_num) {

                $thisPink = PinkModel::findOne($pinkId);
                $productName = ArrayHelper::toArray(json_decode($thisPink['product_snap']))['name'];
                $pinkNumber = $thisPink['total_num'];
                $leader = $thisPink['user_name'];
                $takeList = PinkPartakeModel::find()
                    ->select(['app_open_id', 'order_id'])
                    ->where(['status' => StatusEnum::ENABLED, 'pink_id' => $pinkId])
                    ->all();
                if (count($takeList)) {
                    $orderIds = array_column($takeList, 'order_id');
                    foreach ($orderIds as $n => $id) {
                        BasicOrderInfoModel::updateAll(['status' => OrderStatusEnum::UN_CHECK], ['id' => $id, 'distribute' => StatusEnum::ENABLED]);
                        BasicOrderInfoModel::updateAll(['status' => OrderStatusEnum::UN_SEND], ['id' => $id, 'distribute' => StatusEnum::DISABLED]);
                    }
                    foreach ($takeList as $m => $parTake) {
                        SendWxMsgService::pinkResultMessage(
                            $parTake['app_open_id'], $productName, $pinkNumber, $leader);
                    }
                }
            }

        }
    }

}
/**********************End Of Product 服务层************************************/


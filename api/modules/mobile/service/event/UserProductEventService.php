<?php

namespace api\modules\mobile\service\event;

use api\modules\mobile\models\event\OrderPayEvent;
use api\modules\mobile\models\forms\UserProductModel;
use fanyou\enums\StatusEnum;
use fanyou\tools\ArrayHelper;
use Yii;


/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-03-23
 */
class UserProductEventService
{
    public function __construct()
    {
    }

    /*********************Product模块服务层************************************/
    /**
     * @param OrderPayEvent $event
     * @throws \yii\db\Exception
     */
    public static function batchAdd(OrderPayEvent $event)
    {
        $order = $event->orderInfo;
        $connectSnap = $order['connect_snap'];
        if (!empty($connectSnap)) {
            $connectArray = ArrayHelper::toArray(json_decode($connectSnap));
            $connectSnap = json_encode(['name' => $connectArray['name'], 'telephone' => $connectArray['telephone']]);
        }
        $stockList = json_decode($order['cart_snap']);
        $field = ['product_id', 'user_id', 'number', 'product_name', 'status', 'connect_snap', 'created_at', 'updated_at'];
        $rows = [];
        $now = time();
        $stockList = ArrayHelper::toArray($stockList);
        foreach ($stockList as $k => $value) {

            $v['product_id'] = $value['id'];
            $v['user_id'] = $order->user_id;
            $v['number'] = $value['number'];
            $v['product_name'] = $value['name'];
            $v['status'] = StatusEnum::ENABLED;
            $v['connect_snap'] = $connectSnap;

            $v['created_at'] = $now;
            $v['updated_at'] = $now;
            //ksort($v);
            $rows[] = $v;
        }
        // 批量写入数据
        //sort($field);
        !empty($rows) && Yii::$app->db->createCommand()->batchInsert(UserProductModel::tableName(), $field, $rows)->execute();

    }

}
/**********************End Of Product 服务层************************************/


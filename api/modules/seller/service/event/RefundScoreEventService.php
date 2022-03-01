<?php

namespace api\modules\seller\service\event;

use api\modules\seller\models\forms\BasicOrderInfoModel;
use api\modules\seller\models\forms\UserInfoModel;
use api\modules\seller\models\forms\UserScoreDetailModel;
use api\modules\seller\service\BasicService;
use api\modules\seller\models\event\OrderEvent;
use fanyou\enums\entity\ScoreTypeEnum;
use fanyou\enums\NumberEnum;
use fanyou\enums\StatusEnum;
use yii\db\Expression;


/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-03-23
 */
class RefundScoreEventService extends BasicService
{
    public function __construct()
    {
    }
    /*********************Product模块服务层************************************/
    /**
     * @param OrderPayEvent $event
     * @throws \Throwable
     */
    public static function refundScoreDetail(OrderEvent $event)
    {
        $orderInfo = BasicOrderInfoModel::findOne($event->id);
        $userId = $orderInfo->user_id;
        $userInfo = UserInfoModel::findOne($userId);
        $score = $orderInfo->product_score - $orderInfo->deduct_score;
        if ($score == 0) {
            return;
        }
        $deductScore = new UserScoreDetailModel();
        $deductScore->user_id = $userId;
        $deductScore->type = ScoreTypeEnum::REFUND;
        if ($score > 0) {
            $deductScore->is_deduct = StatusEnum::COME_OUT;
        }
        if ($score < 0) {
            $deductScore->is_deduct = StatusEnum::COME_IN;
        }
        $deductScore->order_id = $orderInfo->id;
        $deductScore->score = NumberEnum::N_ONE*$score  ;
        $deductScore->last_score = $userInfo->total_score;
        $deductScore->insert();

        UserInfoModel::updateAll(['total_score' => new Expression('`total_score` - ' . $score)
        ], ['id' => $userId]);
    }


}
/**********************End Of Product 服务层************************************/


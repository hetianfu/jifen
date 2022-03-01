<?php

namespace api\modules\mobile\service\event;

use api\modules\mobile\models\event\OrderPayEvent;
use api\modules\mobile\models\forms\BasicOrderInfoModel;
use api\modules\mobile\models\forms\UserInfoModel;
use api\modules\mobile\models\forms\UserScoreDetailModel;
use api\modules\mobile\service\BasicService;
use api\modules\mobile\service\UserScoreConfigService;
use fanyou\enums\entity\ScoreConfigEnum;
use fanyou\enums\entity\ScoreTypeEnum;
use fanyou\enums\StatusEnum;
use Yii;
use yii\db\Expression;


/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-03-23
 */
class ScoreEventService  extends BasicService
{
    public function __construct()
    {
    }

    /*********************Product模块服务层************************************/
    /**
     * @param OrderPayEvent $event
     * @throws \Throwable
     */
    public static  function addUserScoreDetail(OrderPayEvent  $event)
    {
        $orderInfo=$event->orderInfo;
        $userId=$orderInfo->user_id;
        $userInfo=UserInfoModel::findOne($userId);

        $presentScore=$orderInfo->product_score;
        $scoreConfigService = new UserScoreConfigService();
        $scoreConfig=$scoreConfigService->getScoreConfig();
        if(!empty($scoreConfig)){
            $presentType=$scoreConfig[ScoreConfigEnum::PRESENT_TYP];
            if(empty($presentType)){
                $presentScore= intval(
                    $orderInfo->pay_amount*(isset($scoreConfig[ScoreConfigEnum::PRESENT_RATIO])?$scoreConfig[ScoreConfigEnum::PRESENT_RATIO]:0));
                BasicOrderInfoModel::updateAll(['product_score'=>$presentScore],['id'=>$orderInfo->id]);
            }
        }
        $score=$presentScore-$orderInfo->deduct_score;
        $field = [ 'user_id', 'type', 'is_deduct', 'order_id', 'score' ,'last_score', 'created_at', 'updated_at'];
        $rows=[];
        $now=time();
        if($orderInfo->deduct_score!=0){
            $deductScore['user_id']=$userId;
            $deductScore['type']= ScoreTypeEnum::DEDUCT ;
            $deductScore['is_deduct']=StatusEnum::COME_OUT;
            $deductScore['order_id']=$orderInfo->id;
            $deductScore['score']=$orderInfo->deduct_score*StatusEnum::COME_OUT;
            $deductScore['last_score']=$userInfo->total_score;
            $deductScore['created_at']=$now;
            $deductScore['updated_at']=$now;
            $rows[]=$deductScore;
        };
         if( $presentScore!=0 ){
             $productScore['user_id']=$userId;
             $productScore['type']= ScoreTypeEnum::ORDER ;
             $productScore['is_deduct']=StatusEnum::COME_IN;
             $productScore['order_id']=$orderInfo->id;
             $productScore['score']=$presentScore;
             $productScore['last_score']=$userInfo->total_score-$orderInfo->deduct_score;
             $productScore['created_at']=$now;
             $productScore['updated_at']=$now;
             $rows[]=$productScore;
        }
        // 批量写入数据
        !empty($rows) && Yii::$app->db->createCommand()->batchInsert(UserScoreDetailModel::tableName(), $field, $rows)->execute();

        UserInfoModel::updateAll(['total_score' => new Expression('`total_score` + '.$score)
            ,'cost_amount' => new Expression('`cost_amount` + '.$orderInfo->pay_amount)
            ,'cost_number' => new Expression('`cost_number` + 1' )
        ], ['id'=>$userId]);
    }


}
/**********************End Of Product 服务层************************************/


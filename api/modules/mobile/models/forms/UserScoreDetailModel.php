<?php

namespace api\modules\mobile\models\forms;
/**
 * This is the model class for table "{{%user_score_detail}}".
 *
 * @property  $id  ;
 * @property string $user_id userId 用户Id;
 * @property int $score  积分数量;
 * @property int $last_score lastScore 上次结余;
 * @property string $order_id orderId 订单Id;
 * @property string $type  PLANT-平台消费类型;
 * @property int $created_at createdAt ;
 * @property int $updated_at updatedAt ;
 * @property mixed is_deduct
 */
class UserScoreDetailModel extends Base
{
    public static function tableName()
    {
        return '{{%user_score_detail}}';
    }

    public function rules()
    {
        $rules = parent::rules();
        return array_merge([
            [['id'], 'required', 'on' => ['update']],
            [['user_id', 'order_id', 'type',], 'string', 'on' => ['default', 'update']],
            [['score', 'last_score', 'is_deduct'], 'integer', 'on' => ['default', 'update']],
        ], $rules);
    }

    public function fields()
    {
        $fields = parent::fields();
        isset($this->user_id) && $fields['userId'] = 'user_id';
        isset($this->last_score) && $fields['lastScore'] = 'last_score';
        isset($this->is_deduct) && $fields['isDeduct'] = 'is_deduct';
        isset($this->order_id) && $fields['orderId'] = 'order_id';
        unset($fields['userId'], $fields['last_score'], $fields['is_deduct'], $fields['order_id']);

        return $fields;
    }

    public function setAttributes($values, $safeOnly = true)
    {
        parent::setAttributes($values, $safeOnly);
    }
}


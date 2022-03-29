<?php
namespace api\modules\seller\models\forms;
/**
 * This is the model class for table "{{%user_score_detail}}".
 *
 * @property  $id  ;
 * @property string $user_id userId 用户Id;
 * @property int  $score  积分数量;
 * @property int  $last_score lastScore 上次结余;
 * @property int  $is_deduct;
 * @property string $order_id orderId 订单Id;
 * @property string $type  PLANT-平台消费类型;
 * @property int  $created_at createdAt ;
 * @property int  $updated_at updatedAt ;
 */
class UserScoreDetailModel extends Base{
    public static function tableName()
    {
        return '{{%user_score_detail}}';
    }

    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['id'],'required','on' => [ 'update' ]],
            [['user_id','order_id','type',],'string','on' => [ 'default','update']],
            [['score','last_score','is_deduct' ], 'integer','on' => [ 'default','update']],
        ], $rules);
    }


    public function fields(){
        $fields = parent::fields();
        isset($this->userSnap)&&$fields['userSnap']='userSnap';
        return $fields;
    }

    public function setAttributes($values, $safeOnly = true) {
        parent::setAttributes($values, $safeOnly);
    }

    public function getUserSnap(){
        return $this->hasOne( UserInfoModel::class,['id'=>'user_id'])->select(['id','nick_name','code','head_img','telephone' ]);
    }


}


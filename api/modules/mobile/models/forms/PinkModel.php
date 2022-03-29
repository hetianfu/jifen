<?php
namespace api\modules\mobile\models\forms;
/**
 * This is the model class for table "{{%pink}}".
 *
 * @property  $id  ;
 * @property string $user_id userId 用户id，发起人Id;
 * @property int  $total_num totalNum 购买商品个数;
 * @property int  $currency_num currencyNum 当前人数;
 * @property int  $product_id productId 产品id;
 * @property string $end_time endTime 结束时间;
 * @property  $is_refund isRefund 是否退款 0未退款 1已退款;
 * @property  $status  状态0进行中 1已完成-1失败;
 * @property  $product_snap;
 * @property int  $created_at createdAt ;
 * @property int  $updated_at updatedAt ;
 * @property int is_effect
 * @property  $user_name
 */
class PinkModel extends Base{
    public static function tableName()
    {
        return '{{%pink}}';
    }
    public $partakeList;
    public $myPartake;
    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['id'],'required','on' => [ 'update' ]],
            [['user_id','user_name',],'string','on' => [ 'default','update']],
            [['total_num','currency_num','product_id', 'end_time','status','is_effect'], 'integer','on' => [ 'default','update']],
            [['product_snap',],'safe','on' => [ 'default','update']],
        ], $rules);
    }


    public function fields(){
        $fields = parent::fields();
        $fields['leftTime']=function (){
            $leftTime=$this->end_time-time();
            return $leftTime<0?0:$leftTime;
        };
        !is_null($this->myPartake)&&$fields['myPartake']='myPartake';
        !empty($this->partakeList)&&$fields['partakeList']='partakeList';
        unset($fields['user_id']);unset($fields['user_name']);
        return $fields;
    }

    public function setAttributes($values, $safeOnly = true) {
        parent::setAttributes($values, $safeOnly);
    }
}


<?php
namespace api\modules\seller\models\forms;
use fanyou\enums\WalletStatusEnum;

/**
 * This is the model class for table "{{%user_commission_detail}}".
 *
 * @property  $id  ;
 * @property string $user_id userId 用户Id;
 * @property  $amount  用户总佣金;
 * @property int  $is_deduct isDeduct 是否负数;
 * @property string $type  类型：CONSUME 消费, DRAW提现;
 * @property string $provider_id providerId 提供者Id,;
 * @property string $order_id orderId 关联订单Id;
 * @property string $remark  备注;
 * @property int  $created_at createdAt 添加时间;
 * @property int  $updated_at updatedAt 更新时间;
 * @property int $status
 * @property string $fail_msg
 * @property string $real_name
 * @property string $open_id
 * @property string $pay_type
 * @property  $level
 */
class UserCommissionDetailModel extends Base{
    public static function tableName()
    {
        return '{{%user_commission_detail}}';
    }

    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['id'],'required','on' => [ 'update' ]],
            [['type','is_deduct','amount'],'required','on' => [ 'default' ]],
            [['user_id','type','provider_id','order_id','fail_msg','real_name','real_name','pay_type','remark','fail_msg'],'string','on' => [ 'default','update']],
            [['is_deduct', 'status','level'], 'integer','on' => [ 'default','update']],
            [['amount', ], 'number','on' => [ 'default','update']],

        ], $rules);
    }


    public function fields(){
        $fields = parent::fields();
        isset($this->userSnap)&&  $fields['userSnap']='userSnap';
        return $fields;
    }

    public function setAttributes($values, $safeOnly = true) {
        parent::setAttributes($values, $safeOnly);
    }

    public function getUserSnap(){
        return $this->hasOne( UserInfoModel::class,['id'=>'user_id'])->select(['id','nick_name','code','head_img','telephone' ]);
    }
    public function add(){
        $result=parent::insert();
        if( $result ){
            $event=new SystemFinanceModel();
            $event->user_id=$this->user_id;
            $event->nick_name= $this->real_name;
            $event->type=$this->type;
            $event->amount=$this->amount;
            $event->content=WalletStatusEnum::getDescribe($this->type);//  '用户钱包变动';
            $event->insert();
        }
        return $result;
    }
}


<?php
namespace api\modules\mobile\models\forms;
use fanyou\enums\WalletStatusEnum;

/**
 * This is the model class for table "{{%user_wallet_detail}}".
 *
 * @property string $id  分销时，为订单id;
 * @property string $user_id userId 用户Id;
 * @property string $real_name realName 名称;
 * @property int  $type CONSUME  消费 CHARGE充值;
 * @property int  $is_deduct  -1出帐 1入帐;
 *
 * @property string $pay_type payType bank = 银行卡 alipay = 支付宝wx=微信;
 * @property string $bank_code bankCode 银行卡;
 * @property string $bank_address bankAddress 开户地址;
 * @property string $alipay_code alipayCode 支付宝账号;
 * @property  $amount  提现金额;
 * @property string $remark  备注;
 * @property  $balance  钱包余额;
 * @property string $fail_msg failMsg 无效原因;
 * @property int  $fail_time failTime 无效时间;
 * @property int  $status  -1 未通过 0 审核中 1 已提现;
 * @property string $open_id openId 提现微信号;
 * @property int  $created_at createdAt 添加时间;
 * @property int  $updated_at updatedAt 更新时间;
 * @property string operator
 */
class UserWalletDetailModel extends Base{

    public static function tableName()
    {
        return '{{%user_wallet_detail}}';
    }

    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['id'],'required','on' => [ 'update' ]],
            [['amount'],'required','on' => [ 'default' ]],
            [['id','user_id','type','real_name','pay_type','bank_code','bank_address','alipay_code','remark', 'open_id','operator'],'string','on' => [ 'default','update']],
            [[ 'amount', ], 'number','on' => [ 'default','update']],
            [[ 'is_deduct', ], 'integer','on' => [ 'default','update']],
        ], $rules);
    }


    public function fields(){
        $fields = parent::fields();
        return $fields;
    }
    public function setAttributes($values, $safeOnly = true) {
        parent::setAttributes($values, $safeOnly);
    }
    public function add(){
       if( parent::insert() ){
        $event=new SystemFinanceModel();
        $event->user_id=$this->user_id;
        $event->nick_name= $this->real_name;
        $event->type=$this->type;
        $event->pay_type=$this->pay_type;

        $event->amount=$this->amount;
        $content=WalletStatusEnum::getDescribe($event->type);

        $event->content=$content;
        $event->insert();
       }
    }
}


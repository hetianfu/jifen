<?php
//namespace api\modules\seller\models\forms;
///**
// * This is the model class for table "{{%user_draw_apply}}".
// *
// * @property  $id  分销时，为订单id;
// * @property string $user_id userId 用户Id;
// * @property string $real_name realName 名称;
// * @property string $extract_type extractType bank = 银行卡 alipay = 支付宝wx=微信;
// * @property string $bank_code bankCode 银行卡;
// * @property string $bank_address bankAddress 开户地址;
// * @property string $alipay_code alipayCode 支付宝账号;
// * @property  $amount  提现金额;
// * @property string $remark  备注;
// * @property string $fail_msg failMsg 无效原因;
// * @property int  $fail_time failTime 无效时间;
// * @property int  $status  -1 未通过 0 审核中 1 已提现;
// * @property string $open_id openId 提现微信号;
// * @property int  $created_at createdAt 添加时间;
// * @property int  $updated_at updatedAt 更新时间;
// */
//class UserDrawApplyModel extends Base{
//    public static function tableName()
//    {
//        return '{{%user_draw_apply}}';
//    }
//
//    public function rules(){
//        $rules = parent::rules();
//        return array_merge([
//            [['id'],'required','on' => [ 'update' ]],
//            [['user_id','real_name','extract_type','bank_code','bank_address','alipay_code','remark','fail_msg','open_id',],'string','on' => [ 'default','update']],
//            [['fail_time','status', ], 'integer','on' => [ 'default','update']],
//        ], $rules);
//    }
//
//
//    public function fields(){
//        $fields = parent::fields();
////        $fields['userId']='user_id';
////        $fields['realName']='real_name';
////        $fields['extractType']='extract_type';
////        $fields['bankCode']='bank_code';
////        $fields['bankAddress']='bank_address';
////        $fields['alipayCode']='alipay_code';
////        $fields['failMsg']='fail_msg';
////        $fields['failTime']='fail_time';
////        $fields['openId']='open_id';
////        unset( $fields['user_id'],$fields['real_name'],$fields['extract_type'],$fields['bank_code'],$fields['bank_address'],$fields['alipay_code'],$fields['fail_msg'],$fields['fail_time'],$fields['open_id'],);
//        return $fields;
//    }
//
//    public function setAttributes($values, $safeOnly = true) {
////        $values['userId']&&$values['user_id']=$values['userId'];
////        $values['realName']&&$values['real_name']=$values['realName'];
////        $values['extractType']&&$values['extract_type']=$values['extractType'];
////        $values['bankCode']&&$values['bank_code']=$values['bankCode'];
////        $values['bankAddress']&&$values['bank_address']=$values['bankAddress'];
////        $values['alipayCode']&&$values['alipay_code']=$values['alipayCode'];
////        $values['failMsg']&&$values['fail_msg']=$values['failMsg'];
////        $values['failTime']&&$values['fail_time']=$values['failTime'];
////        $values['openId']&&$values['open_id']=$values['openId'];
//        parent::setAttributes($values, $safeOnly);
//    }
//}
//

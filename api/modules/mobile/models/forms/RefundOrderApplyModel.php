<?php
namespace api\modules\mobile\models\forms;
/**
 * This is the model class for table "{{%refund_order_apply}}".
 *
 * @property string $id  订单Id;
 * @property string $origin_status originStatus 退单前订单状态;
 * @property int  $refund_amount refundAmount 退单金额;
 * @property int  $order_amount orderAmount  订单金额;
 * @property string $user_id userId 用户id;
 * @property string $user_snap userSnap 用户镜像;
 * @property string $type  MANAGER 后台更改,MOBILE 用户手机端申请;
 * @property string $pay_type 支付方式;
 * @property int  $status  申请状态 0,初使化 1退单成功，-1拒绝退单;
 * @property string $remark  申请退单理由;
 * @property string $content  详细内容;
 * @property string $fail_msg failMsg 拒绝理由;
 * @property int  $created_at createdAt ;
 * @property int  $updated_at updatedAt ;
 * @property string refund_id
 * @property string pay_order_id
 */
class RefundOrderApplyModel extends Base{
    public static function tableName()
    {
        return '{{%refund_order_apply}}';
    }

    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['id'],'required','on' => [ 'update' ]],
            [['id','origin_status','user_id','user_snap','pay_type','type','remark','content','fail_msg','refund_id','pay_order_id'],'string','on' => [ 'default','update']],
            [['refund_amount','order_amount','status', ], 'number','on' => [ 'default','update']],
        ], $rules);
    }


    public function fields(){
       $fields = parent::fields();
       return $fields;
    }

    public function setAttributes($values, $safeOnly = true) {
        parent::setAttributes($values, $safeOnly);
    }
}


<?php
namespace api\modules\mobile\models\forms;

/**
 * This is the model class for table "{{%basic_order_info}}".
 *
 * @property string $id  订单号;
 * @property string $short_order_no shortOrderNo 店铺订单简写;
 * @property int  $order_add_amount orderAddAmount 每单加价金额;
 * @property int  $origin_amount originAmount 订单原金额;
 * @property int  $pay_amount payAmount 支付金额;
 * @property int  $freight_amount freightAmount 订单邮递费用;
 * @property int  $order_cost_amount orderCostAmount ;
 * @property int  $discount_amount discountAmount 折扣金额;
 * @property string $discount_snap discountSnap 折扣镜像;
 * @property int  $small_change smallChange 订单抹零;
 * @property string $seller_type sellerType 服务商facilitator--普通商户normal;
 * @property string $type  订单类型 MALL 网络商城订单  MARKET便利店订单  HOTEL餐饮订单;
 * @property string $search_word
 * @property string $order_product_type orderProductType 订单里面商品的类型 COUNT 、GROUP_BUY;
 * @property string $distribute   DISTRIBUTE----自提;
 * @property string $status  状态: unpaid 待支付’preorder'预定状态  'init'订单初使化   'ordered'订单进行中 paid 订单支付，但未确认支付成功  'closed'  订单完成   ‘settled’ 组合支付状态 'refund'退单  'cancelled' 取消订单;
 * @property int $is_reply  是否评论0-未评论1-评论
 * @property string $billing_id billingId  ;
 * @property string $order_way orderWay 该订单用于做什么 ORDER 正常订单  COPARTNET 购买合伙人;
 * @property string $pay_type 支付方式 ;
 * @property string $merchant_id merchantId 对于平台订单,是收款商户 Id;
 * @property string $shop_snap shopSnap 店铺镜像;
 * @property string $user_id userId 用户Id;
 * @property string $user_snap
 * @property int $is_vip 是否vip订单;
 * @property string $connect_snap connectSnap 联系人镜像;
 * @property int  $receive_side receiveSide 0--平台收款，1店铺本身收款;
 * @property string $prepay_id prepayId 微信拉起支付的prePay;
 * @property string $order_snap orderSnap ;
 * @property string $cart_snap cartSnap 购物车镜像（productId----productSnap）;
 * @property string $address_snap addressSnap ;
 * @property string $cooperate_shop_id cooperateShopId 社区团购收货点id;
 * @property string $cooperate_shop_address_snap cooperateShopAddressSnap 社区团购收货点地址;
 * @property int  $refund_amount refundAmount 退单金额;
 * @property string $ref_order_ids refOrderIds 退单关联;
 * @property string $left_message leftMessage 买家留言;
 * @property int  $show_bar_qrcode showBarQrcode 是否生成条码，-1不生成;
 * @property int  $refund_able refundAble 是否支持退款，1支持退单， 0不支持退单;
 * @property string $remark  商家备注;
 * @property  $create_time createTime 创建时间;
 * @property  $paid_time paidTime ;
 * @property  $open_time openTime ;
 * @property  $close_time closeTime 结单时间;
 * @property  $update_time updateTime ;
 * @property int un_paid_seconds
 * @property mixed created_at
 * @property  int $product_score  商品积分
 * @property mixed user_coupon_id
 * @property mixed deduct_score
 * @property mixed extend_snap
 * @property  $appoint_time
 * @property string paid_user_id
 * @property mixed|null source_id
 * @property mixed|null phone_mode
 *
 */
class BasicOrderInfoModel extends Base{
    public static function tableName()
    {
        return '{{%basic_order_info}}';
    }
    public $is_proxy_pay;
    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['id'],'required','on' => [ 'update' ]],
            [['id','phone_mode','supply_name','seller_type','type','order_product_type','status','billing_id','order_way','merchant_id','paid_user_id'
                ,'shop_snap','user_id', 'connect_snap','prepay_id','order_snap','cart_snap','address_snap','cooperate_shop_id','search_word',
                'cooperate_shop_address_snap','ref_order_ids','left_message','remark','user_coupon_id','pay_type'],'string','on' => [ 'default','update']],
            [[ 'product_score','deduct_score', 'receive_side', 'show_bar_qrcode','refund_able','is_reply'], 'integer','on' => [ 'default','update']],
            [['order_add_amount','origin_amount','product_amount','pay_amount','freight_amount','order_cost_amount','discount_amount','small_change','refund_amount',
                'un_paid_seconds','source_id'], 'number','on' => [ 'default','update']],
            [['short_order_no','created_at','paid_time','open_time','close_time','updated_at','discount_snap','user_snap', 'distribute','is_vip', 'appoint_time','extend_snap' ],'safe','on' => [ 'default','update']],
        ], $rules);
    }


    public function fields(){
        $fields = parent::fields();
        isset($this->is_proxy_pay) && $fields['isProxyPay'] = 'is_proxy_pay';
        isset($this->number) && $fields['number'] = 'number';
        isset($this->pay_amount) && $fields['payAmount'] = 'pay_amount';
        isset($this->left_message) && $fields['leftMessage'] = 'left_message';
        isset($this->cooperate_shop_id) && $fields['cooperateShopId'] = 'cooperate_shop_id';
        isset($this->channel_id) && $fields['channelId'] = 'channel_id';
        isset($this->channel_head_id) && $fields['channelHeadId'] = 'channel_head_id';
        isset($this->origin_amount) && $fields['originAmount'] = 'origin_amount';
        isset($this->freight_amount) && $fields['freightAmount'] = 'freight_amount';
        isset($this->appoint_time) && $fields['appointTime'] = 'appoint_time';
        isset($this->short_order_no) && $fields['shortOrderNo'] = 'short_order_no';
        isset($this->order_way) && $fields['orderWay'] = 'order_way';
        isset($this->order_product_type) && $fields['orderProductType'] = 'order_product_type';
        isset($this->pay_type) && $fields['payType'] = 'pay_type';
        isset($this->shop_id) && $fields['shopId'] = 'shop_id';
        isset($this->is_vip) && $fields['isVip'] = 'is_vip';
        isset($this->is_reply) && $fields['isReply'] = 'is_reply';
        isset($this->refund_able) && $fields['refundAble'] = 'refund_able';
        $fields['paidTime'] = 'paid_time';
        isset($this->show_bar_qrcode) && $fields['showBarQrcode'] = 'show_bar_qrcode';
        isset($this->un_paid_seconds) && $fields['unPaidSeconds'] = 'un_paid_seconds';
        isset($this->express_no) && $fields['expressNo'] = 'express_no';
        isset($this->express_name) && $fields['expressName'] = 'express_name';
        isset($this->discount_amount) && $fields['discountAmount'] = 'discount_amount';
        isset($this->user_id) && $fields['userId'] = 'user_id';
        isset($this->product_amount) && $fields['productAmount'] = 'product_amount';
        isset($this->send_type) && $fields['sendType']='send_type';
        isset($this->deduct_score) && $fields['deductScore']='deduct_score';

        $fields['cartSnap']= function (){
            return  json_decode($this->cart_snap);
        };

        $fields['userSnap']= function (){
            return  json_decode($this->user_snap);
        };
        isset($this->address_snap)&&
        $fields['addressSnap']= function (){
            return  json_decode($this->address_snap);
        };

        !empty($this->connect_snap)&&
        $fields['connectSnap']= function (){
            return  json_decode($this->connect_snap);
        };

        !empty($this->cooperate_shop_address_snap)&&
        $fields['cooperateShopAddressSnap']= function (){
            return  json_decode($this->cooperate_shop_address_snap);
        };




        $fields['discountSnap']=function (){
            return  json_decode($this->discount_snap);
        };
        $fields['unPaidSeconds']=function (){
            $left=$this->un_paid_seconds-time();
            return $left >0 ? $left:0;
        };
        unset($fields['cooperate_shop_address_snap'], $fields['connect_snap'], $fields['discount_snap'], $fields['user_snap'], $fields['address_snap'], $fields['cart_snap']
            , $fields['order_cost_amount'], $fields['left_message'], $fields['cooperate_shop_id'], $fields['channel_id'], $fields['channel_head_id']
            , $fields['pay_amount'], $fields['origin_amount'], $fields['freight_amount'], $fields['appoint_time']
            , $fields['pay_type'], $fields['is_reply'],$fields['shop_id'],$fields['is_vip'],$fields['refund_able'],$fields['show_bar_qrcode']
            , $fields['show_bar_qrcode'], $fields['un_paid_seconds'], $fields['paid_time'],$fields['order_way'],$fields['order_product_type']
            , $fields['express_no'],$fields['express_name'],$fields['short_order_no'],$fields['discount_amount'],$fields['user_id'],$fields['extend_snap']
            , $fields['product_amount'],$fields['send_type'],$fields['send_snap'],
        );
        return $fields;
    }

    public function setAttributes($values, $safeOnly = true) {
        parent::setAttributes($values, $safeOnly);
    }

}


<?php
namespace api\modules\seller\models\forms;
/**
 * This is the model class for table "{{%basic_order_info}}".
 *
 * @property string $id  订单号;
 * @property string $short_order_no shortOrderNo 店铺订单简写;
 * @property int  $order_add_amount orderAddAmount 每单加价金额;
 * @property int  $origin_amount originAmount 订单原金额;
 * @property int  $freight_amount freightAmount 订单邮递费用;
 * @property int  $pay_amount payAmount 支付金额;
 * @property int  $order_cost_amount orderCostAmount ;
 * @property int  $discount_amount discountAmount 折扣金额;
 * @property string $discount_snap discountSnap 折扣镜像;
 * @property int  $small_change smallChange 订单抹零;
 * @property string $seller_type sellerType 服务商facilitator--普通商户normal;
 * @property string $type  订单类型 MALL 网络商城订单  MARKET便利店订单  HOTEL餐饮订单;
 * @property string $order_product_type orderProductType 订单里面商品的类型 COUNT 、GROUP_BUY;
 * @property string $distribute   DISTRIBUTE----自提;
 * @property string $express_no   快递单号--distribute=0时有效
 * @property string $express_name   快递公司
 *
 * @property string $status  状态: unpaid 待支付’preorder'预定状态  'init'订单初使化   'ordered'订单进行中 paid 订单支付，但未确认支付成功  'closed'  订单完成   ‘settled’ 组合支付状态 'refund'退单  'cancelled' 取消订单;
 * @property string $billing_id billingId orderType为union-minor时，parentId生效，为其上级合单id;
 * @property string $order_way orderWay 该订单用于做什么 ORDER 正常订单  COPARTNET 购买合伙人;
 * @property string $pay_type orderType ;
 * @property string $shop_id shopId 对于平台订单,是收款店铺的shopId;
 * @property string $shop_snap shopSnap 店铺镜像;
 * @property string $user_id userId 用户Id;
 * @property string $user_coupon_id
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
 * @property mixed un_paid_seconds
 * @property mixed send_snap
 */
class BasicOrderInfoModel extends Base{
    public $date;
    public $couponInfo;
    public $number;
    public static function tableName()
    {
        return '{{%basic_order_info}}';
    }

    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['id'],'required','on' => [ 'update' ]],
            [['id','phone_mode','supply_name','short_order_no','discount_snap','seller_type','type','order_product_type','distribute','status','billing_id','order_way','express_no','express_name','search_word',
                'pay_type','merchant_id', 'shop_snap','user_id','user_coupon_id', 'connect_snap','prepay_id','order_snap','cart_snap','address_snap','cooperate_shop_id','cooperate_shop_address_snap','ref_order_ids','left_message','remark',],'string','on' => [ 'default','update']],

            [[ 'receive_side', 'show_bar_qrcode','refund_able','is_vip','is_reply', 'appoint_time','send_type' ], 'integer','on' => [ 'default','update']],
            [['order_add_amount','origin_amount','product_amount','freight_amount', 'order_cost_amount','discount_amount','small_change', 'refund_amount',  ], 'number','on' => [ 'default','update']],
            [['created_at','paid_time','open_time','close_time','updated_at','send_snap','source_id'],'safe','on' => [ 'default','update']],
        ], $rules);
    }

    public function fields(){
        $fields = parent::fields();

        isset($this->date)&&$fields['date']='date';
        isset($this->userSnap)&&  $fields['userSnap']='userSnap';
        isset($this->number)&&  $fields['number']='number';
        isset($this->cart_snap)&&
         $fields['cartSnap']= function (){
            return  json_decode($this->cart_snap);
        };
        !empty($this->couponInfo)&&
        $fields['couponInfo']='couponInfo';


        isset($this->address_snap)&&
        $fields['addressSnap']= function (){
            return  json_decode($this->address_snap);
        };

        isset($this->connect_snap)&&
        $fields['connect_snap']= function (){
            return  json_decode($this->connect_snap);
        };
        isset($this->cooperate_shop_address_snap)&&
        $fields['cooperate_shop_address_snap']= function (){
            return  json_decode($this->cooperate_shop_address_snap);
        };

        !empty($this->send_snap)&&
        $fields['sendSnap']= function (){
            return  json_decode($this->send_snap);
        };
        isset($this->un_paid_seconds)&&
        $fields['unPaidSeconds']=function (){
            $left=$this->un_paid_seconds-time();
            return $left >0 ? $left:0;
        };
         return $fields;
    }

    public function setAttributes($values, $safeOnly = true) {

        isset($values['send_snap'])&&$values['send_snap']=json_encode($values['send_snap'],JSON_UNESCAPED_UNICODE);
        parent::setAttributes($values, $safeOnly);
    }

    public function getUserSnap(){
        return $this->hasOne( UserInfoModel::class,['id'=>'user_id'])->select(['id','nick_name','code','head_img','telephone' ]);
    }
}


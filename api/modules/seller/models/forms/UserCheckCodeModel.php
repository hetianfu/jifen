<?php
namespace api\modules\seller\models\forms;
/**
 * This is the model class for table "{{%user_coupon}}".
 *
 * @property  $id  主键;
 * @property string $title  卡券描述;
 * @property string $coupon_detail couponDetail 使用说明;
 * @property string $user_id userId 用户Id;
 * @property string $user_code userCode 用户编号;
 * @property string $product_id productId 商品Id;
 * @property string $order_id orderId 订单Id 不可以为空;
 * @property string $source_path sourcePath 卡券来源SHOP表示店铺为用户添加的优惠券---PLANT表示用户从平台购买的优惠券;
 * @property string $coupon_type couponType 卡券类型， 与product的type属性相对应为：COUNTS，VIR,REAL;
 * @property string $shop_id shopId 卡券属于哪个店铺;
 * @property string $check_shop_id checkShopId 核销店铺Id,;
 * @property  $expire_time expireTime 卡券过期时间;
 * @property int  $coupon_count couponCount ;
 * @property int  $total_number totalNumber 卡券总共使用次数;
 * @property int  $used_number usedNumber 已使用次数;
 * @property int  $left_number leftNumber 卡券剩余次数;
 * @property int  $status  0-可以使用  -1过期   1不可使用;
 * @property string $bar_qrcode barQrcode 用户卡券的二维码,为0时有效;
 * @property int  $create_time createTime ;
 * @property int  $update_time updateTime ;
 * @property mixed orderInfo
 * @property mixed userSnap
 */
class UserCheckCodeModel extends Base{
    public static function tableName()
    {
        return '{{%user_check_code}}';
    }

    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['id'],'required','on' => [ 'update' ]],
            [['title','coupon_detail','user_id','user_code','product_id','order_id','source_path','coupon_type','shop_id','check_shop_id','bar_qrcode',],'string','on' => [ 'default','update']],
            [['coupon_count','total_number','used_number','left_number','status' ], 'integer','on' => [ 'default','update']],
            [['expire_time',],'safe','on' => [ 'default','update']],
        ], $rules);
    }


    public function fields(){
        $fields = parent::fields();
        isset($this->orderInfo)&&$fields['orderInfo']='orderInfo';
        isset($this->userSnap)&&$fields['userSnap']='userSnap';
        return $fields;
    }

    public function setAttributes($values, $safeOnly = true) {

        parent::setAttributes($values, $safeOnly);
    }

    public function getOrderInfo(){
        return $this->hasOne( BasicOrderInfoModel::class,['id'=>'order_id'])->select(['id','product_amount','pay_amount','discount_amount','merchant_id','user_snap','cooperate_shop_address_snap','cart_snap','status']);
    }

    public function getUserSnap(){
        return $this->hasOne( UserInfoModel::class,['id'=>'user_id'])->select(['id','nick_name','code','head_img','telephone' ]);
    }
}


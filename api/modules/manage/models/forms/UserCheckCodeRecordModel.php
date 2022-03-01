<?php
namespace api\modules\manage\models\forms;
use api\modules\seller\models\forms\UserInfoModel;

/**
 * This is the model class for table "{{%user_coupon_record}}".
 *
 * @property  $id  ;
 * @property string $title 抬头;
 * @property string $merchant_id merchantId 店铺Id;
 * @property int  $number  核销数量;
 * @property string $check_source checkSource ;
 * @property string $type  核销订单类型;
 * @property string $order_id orderId 订单号;
 * @property string $user_id userId  用户id;
 * @property string $check_employee_id checkEmployeeId  核销人id;
 * @property string $check_employee_name checkEmployeeName  核销人镜像;
 * @property string $check_shop_id checkShopId  核销店铺;
 * @property string $product_snap productSnap 商品镜像;
 * @property int  $product_price productPrice ;
 * @property int  $shop_bill_amount shopBillAmount ;
 * @property string $bar_qrcode barQrcode 核销码;
 * @property string $remark  描述;
 * @property  $create_time createTime 核销时间;
 * @property  $update_time updateTime ;
 * @property  $userSnap
 */
class UserCheckCodeRecordModel extends Base{
    public static function tableName()
    {
        return '{{%user_check_code_record}}';
    }

    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['id'],'required','on' => [ 'update' ]],
            [['title','merchant_id','check_source','type','order_id','user_id', 'check_employee_id','check_employee_name','check_shop_id','product_snap','bar_qrcode','remark',],'string','on' => [ 'default','update']],
            [['number','product_price','shop_bill_amount', ], 'integer','on' => [ 'default','update']],
        ], $rules);
    }

    public function fields(){
        $fields = parent::fields();

        isset($this->userSnap)&&$fields['userSnap']='userSnap';
        if(!empty($this->product_snap)) {
            $fields['productSnap'] = function () {
                return json_decode($this->product_snap, true);
            };
        }

        return $fields;
    }

    public function setAttributes($values, $safeOnly = true) {

        parent::setAttributes($values, $safeOnly);
    }

    public function getUserSnap(){
        return $this->hasOne( UserInfoModel::class,['id'=>'user_id'])->select(['id','nick_name','code','head_img','telephone' ]);
    }
}


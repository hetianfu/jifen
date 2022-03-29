<?php
namespace api\modules\mobile\models\forms;
/**
 * This is the model class for table "{{%user_coupon_record}}".
 *
 * @property  $id  ;
 * @property string $title 抬头;
 * @property string $shop_id shopId 店铺Id;
 * @property int  $number  核销数量;
 * @property string $check_source checkSource ;
 * @property string $type  核销订单类型;
 * @property string $order_id orderId 订单号;
 * @property string $user_id userId  用户id;
 * @property string $user_snap userSnap  用户镜像;
 * @property string $check_employee_id checkEmployeeId  核销人id;
 * @property string $check_employee_snap checkEmployeeSnap  核销人镜像;
 * @property string $check_shop_id checkShopId  核销店铺;
 * @property string $product_snap productSnap 商品镜像;
 * @property int  $product_price productPrice ;
 * @property int  $shop_bill_amount shopBillAmount ;
 * @property string $bar_qrcode barQrcode 核销码;
 * @property string $remark  描述;
 * @property  $create_time createTime 核销时间;
 * @property  $update_time updateTime ;
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
            [['title','shop_id','check_source','type','order_id','user_id','user_snap','check_employee_id','check_employee_snap','check_shop_id','product_snap','bar_qrcode','remark',],'string','on' => [ 'default','update']],
            [['number','product_price','shop_bill_amount', ], 'integer','on' => [ 'default','update']],
        ], $rules);
    }


    public function fields(){
        $fields = parent::fields();

        if(!empty($this->check_employee_snap)) {
            $fields['check_employee_snap'] = function () {
                return json_decode($this->check_employee_snap, true);
            };
        }
        if(!empty($this->product_snap)) {
            $fields['product_snap'] = function () {
                return json_decode($this->product_snap, true);
            };
        }

        return $fields;
    }

    public function setAttributes($values, $safeOnly = true) {
        parent::setAttributes($values, $safeOnly);
    }
}


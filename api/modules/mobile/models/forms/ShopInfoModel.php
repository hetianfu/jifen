<?php
namespace api\modules\mobile\models\forms;
/**
 * This is the model class for table "{{%shop_info}}".
 *
 * @property string $id  ;
 * @property  $front_img  封面图;
 * @property  $logo  标识;
 * @property  $back_img  背景图;
 * @property int  $merchant_id merchantId 商户Id;
 * @property string $shop_name shopName 店铺名称;
 * @property string $contact_name contactName 联系人姓名;
 * @property string $phone  联系电话;
 * @property string $detail  店铺简介;
 * @property string $lat  经纬度;
 * @property string $lng  经纬度;
 * @property string $address  详细地址;
 * @property string $city  类似于这种规则51.51.570;
 * @property string $business_time businessTime 营业时间;
 * @property int  $post_status postStatus 是否支持配送;
 * @property string $post_start_time postStartTime 配送起始时间;
 * @property string $post_end_time postEndTime 配送结束时间;
 * @property string $post_time postTime 配送时间;
 * @property  $open_time openTime 开业时间;
 * @property int  $on_sale onSale 0停业1，正常营业;
 * @property int  $status  -1 已关闭 0-未上线，1正式营业，;
 * @property  $average  人均消费;
 * @property int  $order_wait_pay_minute orderWaitPayMinute ;
 * @property  $created_at createdAt ;
 * @property  $updated_at updatedAt ;
 */
class ShopInfoModel extends Base{
    public static function tableName()
    {
        return '{{%shop_info}}';
    }

    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['id'],'required','on' => [ 'update' ]],
            [['id','shop_name',  'contact_name','phone','logo', 'detail','lat','lng','address','city',  'business_time','post_start_time','post_end_time','post_time',],'string','on' => [ 'default','update']],
            [['merchant_id','post_status','on_sale','status'  ], 'integer','on' => [ 'default','update']],
            [['front_img','logo','back_img'  ], 'string','on' => [ 'default','update']],
        ], $rules);
    }


    public function fields(){
        $fields = parent::fields();

        return $fields;
    }

}


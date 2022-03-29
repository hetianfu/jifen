<?php
namespace api\modules\seller\models\forms;
/**
 * This is the model class for table "{{%merchant_info}}".
 *
 * @property  $id  ;
 * @property string $name  商户名称;
 * @property string $sub_title subTitle 店铺口号;
 * @property string $contact_name contactName 联系人姓名;
 * @property string $phone  联系电话;
 * @property string $logo  店铺log0;
 * @property string $cover_img coverImg 封面图;
 * @property string $share_img shareImg  店铺分享图;
 * @property string $view  轮播图;
 * @property string $detail  店铺简介;
 * @property string $lat  经纬度;
 * @property string $lng  经纬度;
 * @property string $address  详细地址;
 * @property string $city  类似于这种规则51.51.570;
 * @property string $type  店铺类型 超市--MARKET---其它店SHOP;
 * @property string $industry  行业;
 * @property string $industry_name industryName ;
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
 * @property int  $created_at createdAt ;
 * @property int  $updated_at updatedAt ;
 */
class MerchantInfoModel extends Base{
    public static function tableName()
    {
        return '{{%merchant_info}}';
    }

    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['id'],'required','on' => [ 'update' ]],
            [['name','sub_title','contact_name','phone','logo','cover_img','share_img','detail','lat','lng','address','city','type','industry','industry_name','business_time','post_start_time','post_end_time','post_time',],'string','on' => [ 'default','update']],
            [['post_status','on_sale','status', ], 'integer','on' => [ 'default','update']],
            [['view', ], 'safe','on' => [ 'default','update']],
        ], $rules);
    }


    public function fields(){
        $fields = parent::fields();
        $fields['view']=function (){
            return  json_decode($this->view);
        };
         return $fields;
    }

    public function setAttributes($values, $safeOnly = true) {
        $values['view']&&$values['view']=json_encode($values['view'],JSON_UNESCAPED_UNICODE);
        parent::setAttributes($values, $safeOnly);
    }
}


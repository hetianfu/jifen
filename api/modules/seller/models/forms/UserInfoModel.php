<?php
namespace api\modules\seller\models\forms;
/**
 * This is the model class for table "{{%user_info}}".
 *
 * @property string $id  用户真实Id;
 * @property int  $amount  钱包余额;
 * @property int  $status  状态;
 * @property int  $draw_amount drawAmount 提现金额;
 * @property int  $cost_amount costAmount 消费累计;
 * @property int  $cost_number costNumber 消费次数累计;
 * @property int  $total_score totalScore 总积分;
 * @property string $level_id levelId 等级;
 * @property string $head_img headImg 用户头像;
 * @property string $nick_name nickName 用户昵称;
 * @property string $telephone  电话号码;
 * @property string $birth_day birthDay 会员生日;
 * @property int  $sex  0-女 1男;
 * @property string $mini_app_open_id miniAppOpenId 小程序的openId;
 * @property string $open_id openId ;
 * @property string $union_id unionId 联合Id;
 * @property string $code  用户编号;
 * @property string $role_id roleId 角色Id;
 * @property string $parent_id parentId 引荐人;
 * @property string $parent_type parentType 上家类开型，店铺-SHOP/ 个人- PERSON;
 * @property string $project_id projectId 关注进入的活动Id;
 * @property string $merchant_id merchantId 用户属于代理id;
 * @property int  $is_resource isResource 0未认证，1认证;
 * @property string $source_path sourcePath 用户来源：公众号 WECHAT  小程序 WECHAT-APP 手机注册 TELEPHONE;
 * @property int  $is_sales_person isSalesPerson 是否推广员 1 是;
 * @property string $city  城市;
 * @property int  $created_at createdAt ;
 * @property int  $updated_at updatedAt ;
 * @property  $identify
 * @property mixed|null source_id
 * @property false|mixed|string|null source_json
 */
class UserInfoModel extends Base{
    public $date;
    public static function tableName()
    {
        return '{{%user_info}}';
    }

    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['id'],'required','on' => [ 'update' ]],
            [['id','level_id','phone_mode','head_img','nick_name','telephone','birth_day','mini_app_open_id','open_id','union_id','code','parent_id','parent_type','project_id','merchant_id','source_path','city',],'string','on' => [ 'default','update']],
            [[ 'cost_number','total_score','sex','is_resource','is_sales_person','identify','status','is_vip' ], 'integer','on' => [ 'default','update']],
            [['amount','debt_amount','draw_amount','cost_amount', 'charge_amount'  ], 'number','on' => [ 'default','update']],
            [['created_at', 'updated_at' ,'last_log_in_at','source_id' ], 'integer','on' => [ 'default','update']],
            [['source_json' ,'last_log_in_ip'], 'safe','on' => [ 'default','update']],
        ], $rules);
    }


    public function fields(){
        $fields = parent::fields();
        isset($this->date)&&$fields['date']='date';
        isset($this->source_json)&&$fields['sourceJson']=function (){
            return  json_decode($this->source_json);
        };

         return $fields;
    }

    public function setAttributes($values, $safeOnly = true) {
        parent::setAttributes($values, $safeOnly);
    }
}


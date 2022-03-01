<?php
namespace api\modules\seller\models\forms;
/**
 * This is the model class for table "{{%wechat_user}}".
 *
 * @property int  $id  微信用户id;
 * @property string $unionid  只有在用户将公众号绑定到微信开放平台帐号后，才会出现该字段;
 * @property string $openid  用户的标识，对当前公众号唯一;
 * @property string $min_app_openid minAppOpenid 小程序唯一身份ID;
 * @property string $nickname  用户的昵称;
 * @property string $head_img headImg 用户头像;
 * @property  $sex  用户的性别，值为1时是男性，值为2时是女性，值为0时是未知;
 * @property string $city  用户所在城市;
 * @property string $language  用户的语言，简体中文为zh_CN;
 * @property string $province  用户所在省份;
 * @property string $country  用户所在国家;
 * @property string $remark  公众号运营者对粉丝的备注，公众号运营者可在微信公众平台用户管理界面对粉丝添加备注;
 * @property int  $groupid  用户所在的分组ID（兼容旧的用户分组接口）;
 * @property string $tagid_list tagidList 用户被打上的标签ID列表;
 * @property int  $subscribe  用户是否订阅该公众号标识;
 * @property int  $subscribe_time subscribeTime 关注公众号时间;
 * @property int  $stair  一级推荐人;
 * @property int  $second  二级推荐人;
 * @property int  $order_stair orderStair 一级推荐人订单;
 * @property int  $order_second orderSecond 二级推荐人订单;
 * @property  $now_money nowMoney 佣金;
 * @property string $session_key sessionKey 小程序用户会话密匙;
 * @property string $user_type userType 用户类型;
 * @property int  $created_at createdAt 添加时间;
 * @property  $updated_at updatedAt 更新时间;
 */
class WechatUserModel extends Base{
    public static function tableName()
    {
        return '{{%wechat_user}}';
    }

    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['id'],'required','on' => [ 'update' ]],
            [['unionid','openid','min_app_openid','nickname','head_img','city','language','province','country','remark','tagid_list','session_key','user_type',],'string','on' => [ 'default','update']],
            [['id','groupid','subscribe','subscribe_time','stair','second','order_stair','order_second', ], 'integer','on' => [ 'default','update']],
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


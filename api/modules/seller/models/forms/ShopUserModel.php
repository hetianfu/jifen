<?php
namespace api\modules\seller\models\forms;
/**
 * This is the model class for table "{{%shop_user}}".
 *
 * @property  $id  ;
 * @property string $shop_id shopId 店铺ID;
 * @property string $user_id userId 用户Id ;
 * @property string $head_img headImg 头像;
 * @property string $open_id openId ;
 * @property string $user_name userName Query用户名字;
 * @property string $user_code userCode 用户在平台的编号;
 * @property string $telephone  电话;
 * @property int  $status  -1黑名单  1正常;
 * @property int  $show_order showOrder 排序;
 * @property int  $create_time createTime 创建时间;
 * @property int  $update_time updateTime 修改时间;
 */
class ShopUserModel extends Base{
    public static function tableName()
    {
        return '{{%shop_user}}';
    }

    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['id'],'required','on' => [ 'update' ]],
            [['shop_id','user_id','head_img','open_id','user_name','user_code','telephone',],'string','on' => [ 'default','update']],
            [['status','show_order' ], 'integer','on' => [ 'default','update']],
            [[],'safe','on' => [ 'default','update']],
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


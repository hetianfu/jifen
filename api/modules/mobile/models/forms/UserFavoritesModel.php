<?php
namespace api\modules\mobile\models\forms;
/**
 * This is the model class for table "{{%user_favorites}}".
 *
 * @property  $id  ;
 * @property string $favorites_type favoritesType 收藏主题: PRODUCT商品  INFOMATION资讯;
 * @property string $favorite_id favoriteId 收藏的Id;
 * @property string $favorite_snap favoriteSnap 收藏镜像;
 * @property string $user_id userId 收藏者Id;
 * @property int  $is_phone_message isPhoneMessage 是否手机短信通知;
 * @property string $telephone  接收通知的手机号码;
 * @property int  $created_at createdAt ;
 * @property int  $updated_at updatedAt ;
 */
class UserFavoritesModel extends Base{
    public static function tableName()
    {
        return '{{%user_favorites}}';
    }

    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['id'],'required','on' => [ 'update' ]],
            [['favorites_type','favorite_id','favorite_snap','user_id','telephone',],'string','on' => [ 'default','update']],
            [['is_phone_message', ], 'integer','on' => [ 'default','update']],
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


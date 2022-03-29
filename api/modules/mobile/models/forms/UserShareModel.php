<?php
namespace api\modules\mobile\models\forms;
/**
 * This is the model class for table "{{%user_share}}".
 *
 * @property string $id  ;
 * @property string $user_id userId 用户id;
 * @property string $key_id keyId 分享键值;
 * @property string $key_type keyType 分享键值对应类型;
 * @property string $remark  分享感言;
 * @property string $share_url;
 * @property string $share_snap shareSnap 分享内容;
 */
class UserShareModel extends Base{
    public static function tableName()
    {
        return '{{%user_share}}';
    }

    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['id'],'required','on' => [ 'update' ]],
            [['id','key_id','key_type','remark','share_snap','share_url'],'string','on' => [ 'default','update']],
            [['user_id', ], 'safe','on' => [ 'default','update']],
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


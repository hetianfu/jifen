<?php
namespace api\modules\seller\models\forms;
/**
 * This is the model class for table "{{%user_info_group}}".
 *
 * @property int  $id  ;
 * @property string $group_name groupName  用户组名称;
 * @property int  $vip 是否VIP ;
 * @property int  $show_order showOrder ;
 * @property int  $created_at createdAt ;
 * @property int  $updated_at updatedAt ;
 */
class UserInfoGroupModel extends Base{
    public static function tableName()
    {
        return '{{%user_info_group}}';
    }

    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['id'],'required','on' => [ 'update' ]],
            [['group_name',],'string','on' => [ 'default','update']],
            [['id','vip','show_order'  ], 'integer','on' => [ 'default','update']],
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


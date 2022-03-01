<?php
namespace api\modules\seller\models\forms;
/**
 * This is the model class for table "{{%common_auth_item_child}}".
 *
 * @property int  $role_id roleId 角色id;
 * @property int  $item_id itemId 权限id;
 * @property string $name  别名;
 * @property string $app_id appId 类别;
 * @property string $type  子类别;
 * @property string $addons_name addonsName 插件名称;
 * @property int  $is_menu isMenu 是否菜单;
 */
class AuthItemChildModel extends Base{
    public static function tableName()
    {
        return '{{%common_auth_item_child}}';
    }

    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['id'],'required','on' => [ 'update' ]],
            [['name','app_id','type'],'string','on' => [ 'default','update']],
            [['role_id','item_id','is_menu', ], 'integer','on' => [ 'default','update']],
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


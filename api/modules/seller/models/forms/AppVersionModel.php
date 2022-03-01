<?php
namespace api\modules\seller\models\forms;
/**
 * This is the model class for table "{{%app_version}}".
 *
 * @property int  $id  ;
 * @property string $appid  小程序id;
 * @property string $app_name appName 小程序名称;
 * @property string $version  ;
 * @property string $content  ;
 * @property  $status  ;
 * @property string $update_url updateUrl ;
 * @property int  $created_at createdAt ;
 * @property int  $updated_at updatedAt ;
 */
class AppVersionModel extends Base{
    public static function tableName()
    {
        return '{{%app_version}}';
    }

    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['id'],'required','on' => [ 'update' ]],
            [['appid','app_name','version','content','update_url',],'string','on' => [ 'default','update']],
            [['id', ], 'integer','on' => [ 'default','update']],
        ], $rules);
    }


    public function fields(){
        $fields = parent::fields();
        $fields['appName']='app_name';
        $fields['updateUrl']='update_url';
        unset( $fields['app_name'],$fields['update_url'],);
        return $fields;
    }

    public function setAttributes($values, $safeOnly = true) {
        $values['appName']&&$values['app_name']=$values['appName'];
        $values['updateUrl']&&$values['update_url']=$values['updateUrl'];
        parent::setAttributes($values, $safeOnly);
    }
}


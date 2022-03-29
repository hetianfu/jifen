<?php
namespace api\modules\seller\models\forms;
/**
 * This is the model class for table "{{%common_auth_assignment}}".
 *
 * @property int  $role_id roleId ;
 * @property int  $user_id userId ;
 * @property string $app_id appId 类型;
 * @property int  $created_at createdAt ;
 * @property int  $updated_at updatedAt ;
 */
class CommonAuthAssignmentModel extends Base{
    public static function tableName()
    {
        return '{{%common_auth_assignment}}';
    }

    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['id'],'required','on' => [ 'update' ]],
            [['app_id',],'string','on' => [ 'default','update']],
            [['role_id','user_id','created_at','updated_at', ], 'integer','on' => [ 'default','update']],
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


<?php
namespace api\modules\seller\models\forms;
/**
 * This is the model class for table "{{%common_auth_assignment}}".
 *
 * @property int  $role_id roleId ;
 * @property int  $user_id userId ;
 * @property string $merchant_id merchantId 商户Id;
 * @property string $app_id appId 类型;
 * @property int  $created_at createdAt ;
 * @property int  $updated_at updatedAt ;
 */
class AuthAssignmentModel extends Base{
    public static function tableName()
    {
        return '{{%common_auth_assignment}}';
    }
    public $roleIds;
    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['app_id','merchant_id',],'string','on' => [ 'default','update']],
            [['roleIds','role_id','user_id','created_at','updated_at', ], 'integer','on' => [ 'default','update']],
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


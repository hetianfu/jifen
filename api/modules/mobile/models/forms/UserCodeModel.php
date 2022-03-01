<?php
namespace api\modules\mobile\models\forms;
/**
 * This is the model class for table "{{%user_code}}".
 *
 * @property  $id  ;
 * @property string $merchant_id merchantId 代理Id;
 * @property string $user_id userId 用户Id;
 * @property string $code  编号;
 * @property  $status  0未使用1已使用;
 * @property int  $type  预留字段;
 * @property  $created_at createdAt ;
 * @property  $updated_at updatedAt ;
 */
class UserCodeModel extends Base{
    public static function tableName()
    {
        return '{{%user_code}}';
    }

    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['id'],'required','on' => [ 'update' ]],
            [['merchant_id','user_id','code',],'string','on' => [ 'default','update']],
            [['type', ], 'integer','on' => [ 'default','update']],
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


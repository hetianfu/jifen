<?php
namespace api\modules\mobile\models\forms;
/**
 * This is the model class for table "{{%user_vip}}".
 *
 * @property string $id  充值;
 * @property int  $merchant_id merchantId 商户Id;
 * @property int  $is_permanent isPermanent 0-overdue_at有效 1无效;
 * @property int  $is_vip isVip 1是VIP  0-非VIP;
 * @property int  $overdue_at overdueAt 过期时间;
 * @property int  $created_at createdAt ;
 * @property int  $updated_at updatedAt ;
 */
class UserVipModel extends Base{
    public static function tableName()
    {
        return '{{%user_vip}}';
    }

    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['id'],'required','on' => [ 'update' ]],
            [['id',],'string','on' => [ 'default','update']],
            [['merchant_id','is_permanent','is_vip','overdue_at' ], 'integer','on' => [ 'default','update']],
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


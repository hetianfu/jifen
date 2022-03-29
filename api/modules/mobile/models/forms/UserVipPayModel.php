<?php
namespace api\modules\mobile\models\forms;
/**
 * This is the model class for table "{{%user_vip_pay}}".
 *
 * @property string $id  ;
 * @property int  $merchant_id merchantId 商户Id;
 * @property string $name  套餐名称;
 * @property int  $days  购买有效天数;
 * @property int  $origin_amount originAmount 原价;
 * @property int  $amount  金额;
 * @property int  $is_permanent isPermanent 0是否永久 1是;
 * @property int  $is_vip  -1下架 ，1正常;
 * @property string $remark  ;
 * @property int  $created_at createdAt ;
 * @property int  $updated_at updatedAt ;
 */
class UserVipPayModel extends Base{
    public static function tableName()
    {
        return '{{%user_vip_pay}}';
    }

    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['id'],'required','on' => [ 'update' ]],
            [['id','name','remark','merchant_id',],'string','on' => [ 'default','update']],
            [['days' ,'is_permanent','is_vip', ], 'integer','on' => [ 'default','update']],
            [[ 'origin_amount','amount'   ], 'number','on' => [ 'default','update']],
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


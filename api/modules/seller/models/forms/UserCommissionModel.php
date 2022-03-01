<?php
namespace api\modules\seller\models\forms;
/**
 * This is the model class for table "{{%user_commission}}".
 *
 * @property string $id  用户Id;
 * @property  $amount  用户总佣金;
 * @property  $total_amount totalAmount 累计获取佣金;
 * @property  $debt_amount debtAmount 用户退单亏损佣金;
 * @property int  $debt_number debtNumber 退单数量;
 * @property int  $status  佣金状态 0--失效 1--生效;
 * @property string $remark  备注;
 * @property int  $created_at createdAt 添加时间;
 * @property int  $updated_at updatedAt 更新时间;
 */
class UserCommissionModel extends Base{
    public static function tableName()
    {
        return '{{%user_commission}}';
    }

    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['id'],'required','on' => [ 'update' ]],
            [['id','remark',],'string','on' => [ 'default','update']],
            [['debt_number','status', ], 'integer','on' => [ 'default','update']],
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


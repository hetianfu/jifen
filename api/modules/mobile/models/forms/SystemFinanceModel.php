<?php
namespace api\modules\mobile\models\forms;

/**
 * This is the model class for table "{{%system_finance}}".
 *
 * @property  $id  id;
 * @property string $user_id userId 用户Id;
 * @property string $nick_name nickName 名称;
 * @property string $type  CONSUME消费 CHARGE充值REFUND消费 CHARGE充值;
 * @property  $amount  提现金额;
 * @property string $content  备注;
 * @property int  $created_at createdAt 添加时间;
 * @property int  $updated_at updatedAt 更新时间;
 * @property mixed pay_type
 * @property  string $relation_id
 */
class SystemFinanceModel extends Base{


    public static function tableName()
    {
        return '{{%system_finance}}';
    }

    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['id'],'required','on' => [ 'update' ]],
            [['user_id','nick_name','type','pay_type','content','relation_id'],'string','on' => [ 'default','update']],
            [[ 'amount'], 'number','on' => [ 'default','update']],
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


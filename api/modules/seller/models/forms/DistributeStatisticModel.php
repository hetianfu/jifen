<?php
namespace api\modules\seller\models\forms;
/**
 * This is the model class for table "{{%employee_distribute}}".
 *
 * @property string $id  用户Id;
 * @property int  $level_id levelId 等级Id;
 * @property int  $disciple_number discipleNumber 直接下家人数;
 * @property  $disciple_amount discipleAmount 直推下家贡献;
 * @property  $other_amount otherAmount 间推下家贡献;
 * @property  $self_amount selfAmount 自身贡献;
 * @property  $total_amount totalAmount 总计贡献;
 * @property string $this_month thisMonth 本月;
 * @property  $status  1正常;
 * @property int  $created_at createdAt ;
 * @property int  $updated_at updatedAt ;
 * @property  int  $extra_amount
 * @property array|mixed profit_amount
 */
class DistributeStatisticModel extends Base{
    public static function tableName()
    {
        return '{{%employee_distribute}}';
    }

    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['id'],'required','on' => [ 'update' ]],
            [['id','this_month',],'string','on' => [ 'default','update']],
            [['level_id','disciple_number'  ], 'integer','on' => [ 'default','update']],
            [['disciple_number','other_amount','total_amount','self_amount'  ], 'number','on' => [ 'default','update']],
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


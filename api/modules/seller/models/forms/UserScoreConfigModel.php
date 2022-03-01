<?php
namespace api\modules\seller\models\forms;
/**
 * This is the model class for table "{{%user_score_config}}".
 *
 * @property  $id  ;
 * @property string $merchant_id merchantId 商户Id;
 * @property  $score_deduct scoreDeduct 扣分抵扣比例;
 * @property int  $created_at createdAt ;
 * @property int  $updated_at updatedAt ;
 */
class UserScoreConfigModel extends Base{
    public static function tableName()
    {
        return '{{%user_score_config}}';
    }

    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['id'],'required','on' => [ 'update' ]],
            [['merchant_id',],'string','on' => [ 'default','update']],
            [['score_deduct' ], 'number','on' => [ 'default','update']],
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


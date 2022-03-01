<?php
namespace api\modules\mobile\models\forms;
/**
 * This is the model class for table "{{%user_score}}".
 *
 * @property  $id  用户Id;
 * @property int  $currency_day currencyDay 当前连续签到天数;
 * @property int  $sign_days   累计签到天数;
 * @property int  $sign_score   签到积分数量;
 * @property int  $created_at createdAt ;
 * @property int  $updated_at updatedAt ;
 * @property int $total_score
 * @property int total
 */
class UserScoreModel extends Base{
    public $total_score;
    public $is_sign=0;
    public static function tableName()
    {
        return '{{%user_score}}';
    }

    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['id'],'required','on' => [ 'update' ]],
            [[],'string','on' => [ 'default','update']],
            [['total','currency_day','sign_days','sign_score'  ], 'integer','on' => [ 'default','update']],
        ], $rules);
    }


    public function fields(){
        $fields = parent::fields();
        isset($this->total_score)&&$fields['totalScore']='total_score';
        isset($this->currency_day)&&$fields['currencyDay']='currency_day';
        isset($this->sign_score)&&$fields['signScore']='sign_score';

        isset($this->is_sign)&&$fields['isSign']='is_sign';
        unset($fields['total_score'],$fields['currency_day'],$fields['sign_score'],$fields['is_sign']);
        return $fields;
    }

    public function setAttributes($values, $safeOnly = true) {
        parent::setAttributes($values, $safeOnly);
    }
}


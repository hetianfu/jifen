<?php
namespace api\modules\seller\models\forms;
/**
 * This is the model class for table "{{%user_level}}".
 *
 * @property int  $id  等级id;
 * @property  $discount  折扣;
 * @property int  $level_numer levelNumer 等级号;
 * @property string $level_logo levelLogo  等级图标;
 * @property string $level_name levelName 等级名称;
 * @property int  $status  0不生效 1生效;
 * @property string $remark  等级说明;
 * @property $condition_amount
 * @property $condition_disciple
 * @property int  $created_at createdAt ;
 * @property int  $updated_at updatedAt ;
 */
class UserLevelModel extends Base{
    public static function tableName()
    {
        return '{{%user_level}}';
    }

    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['id'],'required','on' => [ 'update' ]],
            [['level_logo','level_name','remark',],'string','on' => [ 'default','update']],
            [['id','level_number' ,'condition_disciple','pid'], 'integer','on' => [ 'default','update']],
            [['discount','condition_amount' ], 'number','on' => [ 'default','update']],
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


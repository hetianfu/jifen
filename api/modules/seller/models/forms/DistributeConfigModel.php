<?php
namespace api\modules\seller\models\forms;
/**
 * This is the model class for table "{{%distribute_Config}}".
 *
 * @property  $id  ;
 * @property int  $is_every isEvery 是否人 人分销;
 * @property  $first_percent firstPercent 一级返佣;
 * @property  $second_percent secondPercent 二级返佣;
 * @property int  $status  0-禁用 1启用;
 * @property int  $min_draw minDraw 最低提现金额;
 * @property int  $created_at createdAt ;
 * @property int  $updated_at updatedAt ;
 */
class DistributeConfigModel extends Base{
    public static function tableName()
    {
        return '{{%distribute_Config}}';
    }

    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['id'],'required','on' => [ 'update' ]],
            [[],'string','on' => [ 'default','update']],
            [['is_every','status','min_draw', ], 'integer','on' => [ 'default','update']],
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


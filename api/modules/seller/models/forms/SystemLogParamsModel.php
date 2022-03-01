<?php
namespace api\modules\seller\models\forms;
/**
 * This is the model class for table "{{%system_log_params}}".
 *
 * @property  $id  日志Id;
 * @property string $params  操作参数;
 * @property int  $created_at createdAt 操作时间;
 * @property int  $updated_at updatedAt ;
 */
class SystemLogParamsModel extends Base{
    public static function tableName()
    {
        return '{{%system_log_params}}';
    }

    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['id'],'required','on' => [ 'update' ]],
            [['params',],'string','on' => [ 'default','update']],
            [[ ], 'integer','on' => [ 'default','update']],
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


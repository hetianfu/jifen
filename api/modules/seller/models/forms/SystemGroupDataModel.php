<?php
namespace api\modules\seller\models\forms;
/**
 * This is the model class for table "{{%system_group_data}}".
 * @property int  $id  组合数据详情ID;
 * @property int  $gid  对应的数据组id;
 * @property string $value  数据组对应的数据值（json数据）;
 * @property int  $add_time addTime 添加数据时间;
 * @property int  $show_order showOrder  数据排序;
 * @property  $status  状态（1：开启；2：关闭；）;
 * @property int  $created_at createdAt ;
 * @property int  $updated_at updatedAt ;
 */
class SystemGroupDataModel extends Base{
    public static function tableName()
    {
        return '{{%system_group_data}}';
    }

    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['id'],'required','on' => [ 'update' ]],
            [['value',],'string','on' => [ 'default','update']],
            [['id','gid','add_time','show_order','status' ], 'integer','on' => [ 'default','update']],
        ], $rules);
    }

    public function fields(){
        $fields = parent::fields();
        isset($this->value)&&
        $fields['value']=function (){
            return json_decode($this->value);
        };
        return $fields;
    }

    public function setAttributes($values, $safeOnly = true) {
        isset( $values['value'])&&$values['value']=json_encode($values['value'],JSON_UNESCAPED_UNICODE);
        parent::setAttributes($values, $safeOnly);
    }
}


<?php
namespace api\modules\seller\models\forms;
/**
 * This is the model class for table "{{%unit}}".
 *
 * @property  $id  ;
 * @property string $name  单位名称;
 * @property int  $decimals_digits decimalsDigits 小数位（个数大于0，表示小数据精度位数）;
 * @property string $unit_type unitType 单位类型(weight代表重量，小数单位);
 * @property int  $is_system isSystem 0后台添加   -1系统默认 （公斤、瓶）;
 * @property int  $status  预留字段0-正常;
 * @property string $merchant_id  店铺Id;
 * @property int  $create_time createTime ;
 * @property int  $update_time updateTime ;
 */
class UnitModel extends Base{
    public static function tableName()
    {
        return '{{%unit}}';
    }

    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['id'],'required','on' => [ 'update' ]],
            [['name','unit_type','merchant_id',],'string','on' => [ 'default','update']],
            [['decimals_digits','is_system','status',  ], 'integer','on' => [ 'default','update']],

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


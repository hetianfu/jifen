<?php
namespace api\modules\mobile\models\forms;
/**
 * This is the model class for table "{{%system_config}}".
 *
 * @property int  $id  配置id;
 * @property string $menu_name menuName 字段名称;
 * @property string $type  类型(文本框,单选按钮...);
 * @property string $input_type inputType 表单类型;
 * @property int  $config_tab_id configTabId 配置分类id;
 * @property string $required  规则;
 * @property int  $width  多行文本框的宽度;
 * @property int  $high  多行文框的高度;
 * @property string $value  默认值;
 * @property int  $show_order  showOrder 排序;
 * @property  $status  是否隐藏;
 * @property int  $created_at createdAt ;
 * @property int  $updated_at updatedAt ;
 */
class SystemConfigTabValueModel extends Base{
    public static function tableName()
    {
        return '{{%system_config_tab_value}}';
    }

    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['id'],'required','on' => [ 'update' ]],
            [['menu_name','type','input_type' ,'required','value', ],'string','on' => [ 'default','update']],
            [['id','config_tab_id' ,'show_order','status' ], 'integer','on' => [ 'default','update']],
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


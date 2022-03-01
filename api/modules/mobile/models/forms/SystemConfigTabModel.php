<?php
namespace api\modules\mobile\models\forms;
/**
 * This is the model class for table "{{%system_config_tab}}".
 *
 * @property int  $id  配置分类id;
 * @property string $title  配置分类名称;
 * @property string $eng_title engTitle 配置分类英文名称;
 * @property  $status  配置分类状态;
 * @property  $info  配置分类是否显示;
 * @property int  $type  配置类型;
 * @property int  $show_order  showOrder 排序;
 * @property int  $created_at createdAt ;
 * @property int  $updated_at updatedAt ;
 */
class SystemConfigTabModel extends Base{
    public static function tableName()
    {
        return '{{%system_config_tab}}';
    }

    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['id'],'required','on' => [ 'update' ]],
            [['title','eng_title','info' ],'string','on' => [ 'default','update']],
            [['id','type','status','show_order' ], 'integer','on' => [ 'default','update']],
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


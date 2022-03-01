<?php
namespace api\modules\mobile\models\forms;
/**
 * This is the model class for table "{{%system_group}}".
 *
 * @property int  $id  组合数据ID;
 * @property string $name  数据组名称;
 * @property string $info  数据提示;
 * @property string $config_name configName 数据字段;
 * @property string $fields  数据组字段以及类型（json数据）;
 * @property int $show_order  showOrder 排序
 * @property int $type  组合类型：0--系统内置 1--应用设置
 * @property int $limit_number  limitNumber 限制条数量，type=0有效
 * @property int  $created_at createdAt ;
 * @property int  $updated_at updatedAt ;
 * @property mixed spreads
 */
class SystemGroupModel extends Base{

    public static function tableName()
    {
        return '{{%system_group}}';
    }

    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['id'],'required','on' => [ 'update' ]],
            [['name','show_mod','info','config_name'],'string','on' => [ 'default','update']],
            [['id','show_order','limit_number','type' ], 'integer','on' => [ 'default','update']],
            [['spreads' ], 'safe','on' => [ 'default','update']],
        ], $rules);
    }


    public function fields(){
        $fields = parent::fields();
        $fields['spreads']=function (){
            return  json_decode($this->spreads);
        };
        return $fields;
    }

    public function setAttributes($values, $safeOnly = true) {
        parent::setAttributes($values, $safeOnly);
    }
}


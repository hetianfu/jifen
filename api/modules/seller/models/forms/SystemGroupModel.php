<?php
namespace api\modules\seller\models\forms;
/**
 * This is the model class for table "{{%system_group}}".
 *
 * @property int  $id  组合数据ID;
 * @property string $name  数据组名称;
 * @property string $info  数据提示;
 * @property string $config_name configName 数据字段;
 * @property string $fields  数据组字段以及类型（json数据）;
 * @property int $show_order  showOrder 排序
 * @property int $type  组合类型
 * @property int $limit_number  limitNumber 限制条数量
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
            [['name','info','config_name','type' ,],'string','on' => [ 'default','update']],
            [['id','show_order','limit_number','show_mod'], 'integer','on' => [ 'default','update']],
            [['spreads','fields', ], 'safe','on' => [ 'default','update']],
        ], $rules);
    }


    public function fields(){
        $fields = parent::fields();
        $fields['spreads']=function (){
            return  json_decode($this->spreads);
        };
        $fields['fields']=function (){
            return  json_decode($this->fields);
        };
        return $fields;
    }

    public function setAttributes($values, $safeOnly = true) {

        isset($values['spreads'])&&$values['spreads']=json_encode($values['spreads'],JSON_UNESCAPED_UNICODE);
        isset($values['fields'])&&$values['fields']=json_encode($values['fields'],JSON_UNESCAPED_UNICODE);

        parent::setAttributes($values, $safeOnly);
    }
}


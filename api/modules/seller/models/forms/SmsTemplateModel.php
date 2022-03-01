<?php
namespace api\modules\seller\models\forms;
/**
 * This is the model class for table "{{%sms_template}}".
 *
 * @property  $id  ;
 * @property string $name  模版名称;
 * @property string $type  注册：REG;
 * @property string $sign_name signName 签名;
 * @property string $code
 * @property string $template_map templateMap 填充内容;
 * @property int  $created_at createdAt ;
 * @property int  $updated_at updatedAt ;
 */
class SmsTemplateModel extends Base{
    public static function tableName()
    {
        return '{{%sms_template}}';
    }

    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['id'],'required','on' => [ 'update' ]],
            [['name','type','sign_name','code'],'string','on' => [ 'default','update']],
            [[ 'template_map',], 'safe','on' => [ 'default','update']],
        ], $rules);
    }


    public function fields(){
        $fields = parent::fields();
        !empty($this->template_map)&&
        $fields['templateMap']= function (){
            return  json_decode($this->template_map );
        };
        return $fields;
    }

    public function setAttributes($values, $safeOnly = true) {
        $values['template_map']&&$values['template_map']=json_encode($values['template_map'],JSON_UNESCAPED_UNICODE);
        parent::setAttributes($values, $safeOnly);
    }
}


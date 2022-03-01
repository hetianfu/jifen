<?php
namespace api\modules\seller\models\forms;
/**
 * This is the model class for table "{{%pag_config}}".
 *
 * @property  $id  配置分类id;
 * @property string $identify  配置唯一标识;
 * @property string $merchant_id merchantId 商户Id;
 * @property string $name  配置分类名称;
 * @property string $configs  ;
 * @property string $the_groups theGroups ;
 * @property  $status  状态0-未启用 1启用;
 * @property  $is_system isSystem 是否系统级;
 * @property  $type  预留字段;
 * @property int  $sort  排序;
 * @property string $content  简介;
 * @property int  $created_at createdAt ;
 * @property int  $updated_at updatedAt ;
 */
class PagConfigModel extends Base{
    public static function tableName()
    {
        return '{{%pag_config}}';
    }

    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['id'],'required','on' => [ 'update' ]],
            [['identify','merchant_id','name','configs','the_groups',],'string','on' => [ 'default','update']],
            [['sort','refresh_flag','status' ], 'integer','on' => [ 'default','update']],
            [['content','remark' ], 'safe','on' => [ 'default','update']],
        ], $rules);
    }


    public function fields(){
        $fields = parent::fields();
        $fields['merchantId']='merchant_id';
        $fields['groups']='the_groups';
        $fields['isSystem']='is_system';
       !empty($this->content)&&
       $fields['content']=function (){
          return json_decode($this->content) ;
        };
        unset( $fields['merchant_id'],$fields['the_groups'],$fields['is_system']);
        return $fields;
    }

    public function setAttributes($values, $safeOnly = true) {
        $values['merchantId']&&$values['merchant_id']=$values['merchantId'];
        $values['groups']&&$values['the_groups']=$values['groups'];
        $values['isSystem']&&$values['is_system']=$values['isSystem'];
        $values['content']&&$values['content']=json_encode($values['content'],JSON_UNESCAPED_UNICODE);
        parent::setAttributes($values, $safeOnly);
    }
}


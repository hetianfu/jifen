<?php
namespace api\modules\seller\models\forms;
/**
 * This is the model class for table "{{%wechat_message}}".
 *
 * @property  $id  ;
 * @property string $template_id templateId 模版Id;
 * @property string $page  小程序跳转路径;
 * @property string $keys  模版消息关键词主键;
 * @property string $key_word_snap keyWordSnap 关键词镜像;
 * @property string $content  内容;
 * @property string $title  标题;
 * @property int  $status  状态[-1:删除;0:禁用;1启用];
 * @property int  $created_at createdAt ;
 * @property int  $updated_at updatedAt ;
 */
class WechatMessageModel extends Base{
    public static function tableName()
    {
        return '{{%wechat_message}}';
    }

    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['id'],'required','on' => [ 'update' ]],
            [['template_id','page','keys','key_word_snap','content','title',],'string','on' => [ 'default','update']],
            [['status', ], 'integer','on' => [ 'default','update']],
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


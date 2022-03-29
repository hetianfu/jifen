<?php
namespace api\modules\mobile\models\forms;
/**
 * This is the model class for table "{{%wechat_message_config}}".
 *
 * @property  $id  ;
 * @property string $name   英文场景;
 * @property string $title  标题;
 * @property string $message_ids messageIds 模版消息id列表;
 * @property int  $status  状态[-1:删除;0:禁用;1启用];
 * @property int  $created_at createdAt ;
 * @property int  $updated_at updatedAt ;
 */
class WechatMessageConfigModel extends Base{
    public static function tableName()
    {
        return '{{%wechat_message_config}}';
    }

    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['id'],'required','on' => [ 'update' ]],
            [['name','title','message_ids',],'string','on' => [ 'default','update']],
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


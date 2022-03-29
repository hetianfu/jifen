<?php
namespace api\modules\seller\models\forms;
use fanyou\tools\StringHelper;

/**
 * This is the model class for table "{{%channel}}".
 *
 * @property int  $id  渠道id;
 * @property string $name  渠道名称;
 * @property string $brand  渠道品牌;
 * @property string $short_url shortUrl 短连接;
 * @property string $full_url;
 * @property int  $score  积分初使值;
 * @property int  $people  吸粉数量;
 * @property string $content  描述;
 * @property int  $created_at createdAt ;
 * @property int  $updated_at updatedAt ;
 */
class ChannelModel extends Base{
    public static function tableName()
    {
        return '{{%channel}}';
    }

    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['id'],'required','on' => [ 'update' ]],
            [['name','brand','short_url','full_url','content','site_title'],'string','on' => [ 'default','update']],
            [['id','score','people', ], 'integer','on' => [ 'default','update']],
        ], $rules);
    }


    public function fields(){
        $fields = parent::fields();
        $fields['userId']=function (){
            return StringHelper::uuid();
        };
        return $fields;
    }

    public function setAttributes($values, $safeOnly = true) {
        $values['shortUrl']&&$values['short_url']=$values['shortUrl'];
        parent::setAttributes($values, $safeOnly);
    }



}


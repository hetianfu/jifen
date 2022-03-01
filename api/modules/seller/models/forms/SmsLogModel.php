<?php
namespace api\modules\seller\models\forms;
use yii\base\BaseObject;

/**
 * This is the model class for table "{{%sms_log}}".
 *
 * @property  $id  ;
 * @property string $telephone  电话号码;
 * @property string $code  验证码;
 * @property string $sms_type;
 * @property string $sms_code smsCode 短信模版;
 * @property string $ali_request_id aliRequestId ;
 * @property string $ali_message aliMessage ;
 * @property string $ali_code aliCode ;
 * @property int  $created_at createdAt ;
 * @property int  $updated_at updatedAt ;
 * @property array|mixed|object|string|null ali_biz_id
 * @property int|mixed|null status
 * @property mixed|null content
 */
class SmsLogModel extends Base{
    public static function tableName()
    {
        return '{{%sms_log}}';
    }

    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['id'],'required','on' => [ 'update' ]],
            [['telephone','code','sms_code','sms_type','ali_request_id','ali_message','ali_biz_id','content'],'string','on' => [ 'default','update']],
            [[ 'status'], 'integer','on' => [ 'default','update']],
        ], $rules);
    }


    public function fields(){
        $fields = parent::fields();
        return $fields;
    }

    public function setAttributes($values, $safeOnly = true) {
        parent::setAttributes($values, $safeOnly);
    }





    //新建短信
    public function createSms($data){
      return self::create($data);
    }
}


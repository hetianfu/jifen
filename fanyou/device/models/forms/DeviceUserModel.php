<?php
namespace fanyou\device\models\forms;
use api\modules\seller\models\forms\Base;
/**
 * This is the model class for table "{{%device_user}}".
 *
 * @property  $id  ;
 * @property string $device_id deviceId 设备Id;
 * @property string $user_id userId  用户Id;
 * @property string $user_name userName 用户姓名;
 * @property string $mobile  用户电话;
 * @property  $status  状态0失效 1生效;
 * @property string $city  城市;
 * @property string $address  地址;
 * @property int  $created_at createdAt 创建时间;
 * @property int  $updated_at updatedAt 更新时间;
 */
class DeviceUserModel extends Base{
    public static function tableName()
    {
        return '{{%device_user}}';
    }

    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['id'],'required','on' => [ 'update' ]],
            [['device_id','user_id','user_name','mobile','city','address',],'string','on' => [ 'default','update']],
            [[ ], 'integer','on' => [ 'default','update']],
        ], $rules);
    }


    public function fields(){
        $fields = parent::fields();
        $fields['deviceId']='device_id';
        $fields['userId']='user_id';
        $fields['userName']='user_name';
        unset( $fields['device_id'],$fields['user_id'],$fields['user_name'],);
        return $fields;
    }

    public function setAttributes($values, $safeOnly = true) {
        $values['deviceId']&&$values['device_id']=$values['deviceId'];
        $values['userId']&&$values['user_id']=$values['userId'];
        $values['userName']&&$values['user_name']=$values['userName'];
        parent::setAttributes($values, $safeOnly);
    }
}


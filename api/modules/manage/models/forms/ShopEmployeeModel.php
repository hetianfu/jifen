<?php
namespace api\modules\manage\models\forms;
/**
 * This is the model class for table "{{%shop_employee}}".
 *
 * @property string $id  md5(shop_id+userId);
 * @property string $shop_id shopId 店铺ID;
 * @property string $open_id openId 云管家的小程序openId，;
 * @property string $union_id unionId union_id;
 * @property string $mp_open_id mpOpenId 公众号openId;
 * @property int  $mp_send_msg mpSendMsg 0不推送， 1推送;
 * @property string $msg_type msgType 消息类型;
 * @property string $is_admin 角色;
 * @property string $name  员工姓名;
 * @property string $employee_number employeeNumber 员工编码;
 * @property int  $sex  性别:0女 1 男;
 * @property string $email  ;
 * @property string $user_snap userSnap 员工镜像;
 * @property string $telephone  员工预留电话;
 * @property int  $create_time createTime ;
 * @property int  $update_time updateTime ;
 * @property string $password
 */
class ShopEmployeeModel extends Base{
    public static function tableName()
    {
        return '{{%shop_employee}}';
    }
    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['id'],'required','on' => [ 'update' ]],
            [[ 'account','password','merchant_id','shop_id','open_id','union_id','mp_open_id','msg_type','is_admin','name','employee_number','email','user_snap','telephone',],'string','on' => [ 'default','update']],
            [['id','mp_send_msg','sex' ,'status' ], 'integer','on' => [ 'default','update']],
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


<?php
namespace api\modules\seller\models\forms;
/**
 * This is the model class for table "{{%system_log}}".
 *
 * @property  $id  管理员操作记录ID;
 * @property int  $admin_id adminId 管理员id;
 * @property string $admin_name adminName 管理员姓名;
 * @property string $controller  链接;
 * @property string $service
 * @property string $method  访问类型;
 * @property string $path  行为;
 * @property string $ip  登录IP;
 * @property string $type  类型;
 * @property int  $merchant_id merchantId 商户id;
 * @property int  $add_time addTime 操作时间;
 *
 */
class SystemLogModel extends Base{
    public static function tableName()
    {
        return '{{%system_log}}';
    }

    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['id'],'required','on' => [ 'update' ]],
            [['admin_id','admin_name','controller','service','method','path','ip','type',],'string','on' => [ 'default','update']],
            [['merchant_id'  ], 'integer','on' => [ 'default','update']],
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


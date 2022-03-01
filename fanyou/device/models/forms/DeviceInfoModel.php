<?php
namespace fanyou\device\models\forms;
use api\modules\seller\models\forms\Base;
/**
 * This is the model class for table "{{%device_info}}".
 *
 * @property string $id  设备Id;
 * @property string $name  设备名称;
 * @property string $sn  设备型号;
 * @property string $type   计费类型;
 * @property int  $share_flag shareFlag 是否为分享使用 0否，1是;
 * @property  $status  状态0失效 1生效;
 * @property string $detail  详情描述;
 * @property int  $sort  排序;
 * @property int  $activate_time activateTime 激活时间;
 * @property int  $created_at createdAt 创建时间;
 * @property int  $updated_at updatedAt 更新时间;
 */
class DeviceInfoModel extends Base{
    public static function tableName()
    {
        return '{{%device_info}}';
    }

    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['id'],'required','on' => [ 'update' ]],
            [['id','name','sn','type','detail',],'string','on' => [ 'default','update']],
            [['share_flag','sort','activate_time', ], 'integer','on' => [ 'default','update']],
        ], $rules);
    }


    public function fields(){
        $fields = parent::fields();
        $fields['shareFlag']='share_flag';
        $fields['activateTime']='activate_time';
        unset( $fields['share_flag'],$fields['activate_time'],);
        return $fields;
    }

    public function setAttributes($values, $safeOnly = true) {
        $values['shareFlag']&&$values['share_flag']=$values['shareFlag'];
        $values['activateTime']&&$values['activate_time']=$values['activateTime'];
        parent::setAttributes($values, $safeOnly);
    }
}


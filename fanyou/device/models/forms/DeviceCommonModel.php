<?php
namespace fanyou\device\models\forms;
use api\modules\seller\models\forms\Base;

/**
 * This is the model class for table "{{%device_common}}".
 *
 * @property int  $id  ;
 * @property string $name  分类名称;
 * @property string $type  类型ADD,CHARGE,BIND;
 * @property string $url  请求地址;
 * @property string $method  请求方式;
 * @property string $params  请求参数;
 * @property  $status  状态0失效 1生效;
 * @property string $detail  详情描述;
 * @property int  $sort  排序;
 * @property string $notify_url notifyUrl 回调地址，预留;
 * @property int  $created_at createdAt 创建时间;
 * @property int  $updated_at updatedAt 更新时间;
 */
class DeviceCommonModel extends Base{
    public static function tableName()
    {
        return '{{%device_common}}';
    }

    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['id'],'required','on' => [ 'update' ]],
            [['name','type','url','method','params','detail','notify_url',],'string','on' => [ 'default','update']],
            [['id','sort', ], 'integer','on' => [ 'default','update']],
        ], $rules);
    }


    public function fields(){
        $fields = parent::fields();
        $fields['notifyUrl']='notify_url';
        unset( $fields['notify_url'],);
        return $fields;
    }

    public function setAttributes($values, $safeOnly = true) {
        $values['notifyUrl']&&$values['notify_url']=$values['notifyUrl'];
        parent::setAttributes($values, $safeOnly);
    }
}


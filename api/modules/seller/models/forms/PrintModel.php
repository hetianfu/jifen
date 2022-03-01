<?php
namespace api\modules\seller\models\forms;
/**
 * This is the model class for table "{{%print}}".
 *
 * @property string $id  收银台teminal_id;
 * @property int  $disable  是否禁用;
 * @property string $name  收银端设备名称;
 * @property string $brand  打印机品牌;
 * @property string $merchant_id merchantId  商户Id;
 * @property string $type 类型，库房/前台/后厨 ;
 * @property string $owner  所属人-标识;
 * @property string $sim_no simNo 流量卡号码;
 * @property string $print_sn  云打印机编号;
 * @property string $print_key  云打印机密钥;
 * @property string $remark  备注;
 */
class PrintModel extends Base{
    public $status;
    public static function tableName()
    {
        return '{{%print}}';
    }

    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['id'],'required','on' => [ 'update' ]],
            [['name','brand','merchant_id','type','owner','sim_no','print_sn','print_key','remark',],'string','on' => [ 'default','update']],
            [['id','disable', ], 'integer','on' => [ 'default','update']],
        ], $rules);
    }


    public function fields(){
        $fields = parent::fields();
        isset($this->status)&&$fields['status']='status';
        return $fields;
    }

    public function setAttributes($values, $safeOnly = true) {
        parent::setAttributes($values, $safeOnly);
    }
}


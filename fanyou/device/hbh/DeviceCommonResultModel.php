<?php
namespace fanyou\device\hbh;
use api\modules\seller\models\forms\Base;

/**
 * This is the model class for table "{{%device_common_result}}".
 *
 * @property int  $id  ;
 * @property string $name  分类名称;
 * @property string $order_id orderId 商户订单号;
 * @property string $order_type orderType 订单类型;
 * @property string $errmsg  请求方式;
 * @property int  $errcode  错误码,0成功,>0失败;
 * @property string $open_id openId 用户微信ID;
 * @property int  $num  激活数量，默认为1;
 * @property  $money  可分佣金额:单位元;
 * @property int  $created_at createdAt 创建时间;
 * @property int  $updated_at updatedAt 更新时间;
 */
class DeviceCommonResultModel extends Base{
    public static function tableName()
    {
        return '{{%device_common_result}}';
    }

    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['id'],'required','on' => [ 'update' ]],
            [['name','order_id','order_type','errmsg','open_id',],'string','on' => [ 'default','update']],
            [['id','errcode','num', ], 'integer','on' => [ 'default','update']],
        ], $rules);
    }


    public function fields(){
        $fields = parent::fields();
        $fields['orderId']='order_id';
        $fields['orderType']='order_type';
        $fields['openId']='open_id';
        unset( $fields['order_id'],$fields['order_type'],$fields['open_id'],);
        return $fields;
    }

    public function setAttributes($values, $safeOnly = true) {
        $values['orderId']&&$values['order_id']=$values['orderId'];
        $values['orderType']&&$values['order_type']=$values['orderType'];
        $values['openId']&&$values['open_id']=$values['openId'];
        parent::setAttributes($values, $safeOnly);
    }
}


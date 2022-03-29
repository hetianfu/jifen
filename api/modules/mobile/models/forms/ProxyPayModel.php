<?php
namespace api\modules\mobile\models\forms;
/**
 * This is the model class for table "{{%proxy_pay}}".
 *
 * @property string $id  代付Id;
 * @property string $order_id orderId 订单Id;
 * @property string $user_id userId 代付人Id;
 * @property string $mini_app_id miniAppId 代付人小程序id;
 * @property string $prepay_id prepayId 微信统一下单Id;
 * @property string $detail  拉起jssdk;
 * @property int  $created_at createdAt 创建时间;
 * @property int  $updated_at updatedAt 更新时间;
 */
class ProxyPayModel extends Base{
    public static function tableName()
    {
        return '{{%proxy_pay}}';
    }

    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['id'],'required','on' => [ 'update' ]],
            [['id','order_id','user_id','mini_app_id','prepay_id','detail',],'string','on' => [ 'default','update']],
            [[ ], 'integer','on' => [ 'default','update']],
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


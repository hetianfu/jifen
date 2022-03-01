<?php
namespace api\modules\seller\models\forms;
/**
 * This is the model class for table "{{%pink_partake}}".
 *
 * @property int  $id  ;
 * @property  $pink_id pinkId 团购id;
 * @property string $user_id userId 参团人Id;
 * @property string $nick_name nickName 用户昵称;
 * @property string $head_img headImg 用户头像;
 * @property string $order_id orderId 订单id 生成;
 * @property  $price  支付价格;
 * @property  $status  状态0未支付，1已支付;
 * @property int  $created_at createdAt 开始时间;
 * @property int  $updated_at updatedAt 结束时间;
 */
class PinkPartakeModel extends Base{
    public static function tableName()
    {
        return '{{%pink_partake}}';
    }

    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['id'],'required','on' => [ 'update' ]],
            [['user_id','nick_name','head_img','app_open_id','order_id',],'string','on' => [ 'default','update']],
            [['id','pink_id' ,'status'], 'integer','on' => [ 'default','update']],
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


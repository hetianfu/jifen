<?php
namespace api\modules\mobile\models\forms;
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
 * @property  $app_open_id
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

        unset($fields['app_open_id']);
        return $fields;
    }

    public function setAttributes($values, $safeOnly = true) {
        $values['pinkId']&&$values['pink_id']=$values['pinkId'];
        $values['userId']&&$values['user_id']=$values['userId'];
        $values['nickName']&&$values['nick_name']=$values['nickName'];
        $values['headImg']&&$values['head_img']=$values['headImg'];
        $values['orderId']&&$values['order_id']=$values['orderId'];
        parent::setAttributes($values, $safeOnly);
    }
}


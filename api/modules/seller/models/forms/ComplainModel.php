<?php
namespace api\modules\seller\models\forms;
/**
 * This is the model class for table "{{%complain}}".
 *
 * @property  $id  ;
 * @property string $type  投诉类型 POST快递,ORDER订单,SYSTEM平台，GOOD质量，OTHER其它;
 * @property string $content  问题描述;
 * @property string $telephone  联系电话;
 * @property string $name  联系人姓名;
 * @property string $order_id orderId 多个订单号;
 * @property int  $created_at createdAt ;
 * @property int  $updated_at updatedAt ;
 */
class ComplainModel extends Base{
    public static function tableName()
    {
        return '{{%complain}}';
    }

    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['id'],'required','on' => [ 'update' ]],
            [['type','content','telephone','name','order_id','images','merchant_reply_content'],'string','on' => [ 'default','update']],
            [['merchant_reply_time' ], 'integer','on' => [ 'default','update']],
        ], $rules);
    }


    public function fields(){
        $fields = parent::fields();
        $fields['orderId']='order_id';
        isset($this->images)&&
        $fields['images']= function (){
            return  json_decode($this->images);
        };
        unset( $fields['order_id'],);
        return $fields;
    }

    public function setAttributes($values, $safeOnly = true) {
        $values['orderId']&&$values['order_id']=$values['orderId'];
        $values['images']&&$values['images']=json_encode($values['images']);
        parent::setAttributes($values, $safeOnly);
    }
}


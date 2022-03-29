<?php
namespace api\modules\mobile\models\forms;
/**
 * This is the model class for table "{{%coupon}}".
 *
 * @property  $post_id  快递ID ;
 * @property string $title  优惠券名称;
 * @property string $type  优惠券类型;
 * @property string $type_relation_id typeRelationId 类型关联的Ids,以逗号隔开;
 * @property string $seller  商家;
 * @property int  $addtime  发放时间;
 * @property int  $fromtime  领取开始时间;
 * @property int  $totime  领取结束时间;
 * @property int  $is_limit isLimit 是否限量 0--不限量 1限量;
 * @property int  $limit_number limitNumber 发放数量;
 * @property int  $left_number leftNumber 剩余数量;
 * @property  $template_id templateId ;
 * @property string $editor  发放人;
 * @property int  $created_at createdAt ;
 * @property int  $updated_at updatedAt ;
 * @property string $note  ;
 */
class OrderPay extends Base{

    public $postId;
    public $productList;

    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['postId','x',],'string','on' => [ 'default','update']],
            [['productList', ], 'safe','on' => [ 'default','update']],
        ], $rules);
    }


    public function fields(){
        $fields = parent::fields();

        unset(  $fields['postId']);
        return $fields;
    }


}


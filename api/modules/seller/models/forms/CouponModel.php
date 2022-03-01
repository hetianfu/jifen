<?php
namespace api\modules\seller\models\forms;
/**
 * This is the model class for table "{{%coupon}}".
 *
 * @property  $id  ;
 * @property string $title  优惠券名称;
 * @property string $type  优惠券类型;
 * @property string $type_relation_id typeRelationId 类型关联的Ids,以逗号隔开;
 * @property string $merchant_id  商家;
 * @property int  $fromtime  领取开始时间;
 * @property int  $totime  领取结束时间;
 * @property int  $is_once   是否限量;
 * @property int  $limit_number limitNumber 发放数量;
 * @property int  $left_number leftNumber 剩余数量;
 * @property  $template_id templateId ;
 * @property string $editor  发放人;
 * @property int  $created_at createdAt ;
 * @property int  $updated_at updatedAt ;
 * @property string $note  ;
 */
class CouponModel extends Base{
    public static function tableName()
    {
        return '{{%coupon}}';
    }

    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['id'],'required','on' => [ 'update' ]],
            [['template_id'],'required','on' => [ 'default' ]],
            [['editor','note',],'string','on' => [ 'default','update']],
            [[ 'is_permanent','is_pay_send','is_once','limit_number','left_number', 'status' ], 'integer','on' => [ 'default','update']],
            [['order_pay_line','template_id', ], 'number','on' => [ 'default','update']],
            [['fromtime','totime'],'safe','on' => [ 'default','update']],
        ], $rules);
    }


    public function fields(){
        $fields = parent::fields();
        isset($this->template)&&$fields['template']='template';
        return $fields;
    }

    public function setAttributes($values, $safeOnly = true) {

        $values['fromtime']&&$values['fromtime']= strtotime($values['fromtime']) ;
        $values['totime']&&$values['totime']= strtotime($values['totime']);
        parent::setAttributes($values, $safeOnly);
    }

    public function getTemplate() {
            return $this->hasOne( CouponTemplateModel::class,['id'=>'template_id'])->select(['id','type','title','type_relation_id','status' ,'amount','limit_amount','effect_days']);
    }


}


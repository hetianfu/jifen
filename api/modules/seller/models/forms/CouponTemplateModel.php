<?php
namespace api\modules\seller\models\forms;
/**
 * This is the model class for table "{{%coupon_template}}".
 *
 * @property  $id  模版ID;
 * @property string $title  名称;
 * @property string $type  PRODUCT 商品券 ---CATEGORY 品类券 COMMON -通 用券;
 * @property string $type_relation_id typeRelationId 类型关联的Ids,以逗号隔开;
 * @property int  $amount  优惠券面值;
 * @property int  $limit_amount limitAmount 最低消费金额;
 * @property int  $status  是否有效 0- 无效 1 有效;
 * @property int  $show_order showOrder 排序;
 * @property int  $effect_days effectDays 有效天数;
 * @property string $remark  备注;
 * @property int  $created_at createdAt ;
 * @property int  $updated_at updatedAt ;
 */
class CouponTemplateModel extends Base{
    public static function tableName()
    {
        return '{{%coupon_template}}';
    }

    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['id'],'required','on' => [ 'update' ]],
            [['title','type','type_relation_id','remark',],'string','on' => [ 'default','update']],
            [['status','show_order','effect_days', ], 'integer','on' => [ 'default','update']],
            [['amount','limit_amount' ], 'number','on' => [ 'default','update']],
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


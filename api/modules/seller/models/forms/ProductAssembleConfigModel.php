<?php
namespace api\modules\seller\models\forms;
/**
 * This is the model class for table "{{%product_assemble_config}}".
 *
 * @property  $id  团购Id;
 * @property int  $product_id productId 商品id;
 * @property int  $start_time startTime 活动开始时间;
 * @property int  $end_time endTime 活动结束时间;
 * @property int  $term_of_validity termOfValidity 拼团有效期（单位小时）;
 * @property int  $people_num peopleNum 成团人数;
 * @property int  $open_regiment openRegiment 单账号最多开团数;
 * @property int  $join_regiment joinRegiment 单账号最多参团数;
 * @property int  $max_purchase maxPurchase 单账号最多可购买数;
 * @property int  $assemble_type assembleType 0:草稿箱 1:正式发布;
 * @property int  $state  拼团状态。1:进行中;2.结束;
 * @property int  $created_at createdAt 创建时间;
 * @property int  $updated_at updatedAt 更新时间;
 */
class ProductAssembleConfigModel extends Base{
    public static function tableName()
    {
        return '{{%product_assemble_config}}';
    }

    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['id'],'required','on' => [ 'update' ]],
            [[],'string','on' => [ 'default','update']],
            [['product_id','start_time','end_time','term_of_validity','people_num','open_regiment','join_regiment','max_purchase','assemble_type','state', ], 'integer','on' => [ 'default','update']],
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


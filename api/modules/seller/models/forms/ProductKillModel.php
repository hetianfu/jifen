<?php
namespace api\modules\seller\models\forms;
/**
 * This is the model class for table "{{%product_kill}}".
 *
 * @property  $id  秒杀Id;
 * @property string $kill_no killNo 秒杀编号;
 * @property int  $product_id productId 商品id;
 * @property int  $start_time startTime 开始时间;
 * @property int  $end_time endTime 结束时间;
 * @property int  $kill_type killType 1：限时秒杀  2：促销秒杀;
 * @property int  $draft_type draftType 0:草稿箱 1:正式发布;
 * @property int  $max_purchase maxPurchase 单账号最多可购买数;
 * @property int  $state  秒杀状态。1:进行中;2:结束;
 * @property int  $created_at createdAt ;
 * @property int  $updated_at updatedAt 更新时间;
 */
class ProductKillModel extends Base{
    public static function tableName()
    {
        return '{{%product_kill}}';
    }

    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['id'],'required','on' => [ 'update' ]],
            [['kill_no',],'string','on' => [ 'default','update']],
            [['product_id','start_time','end_time','kill_type','draft_type','max_purchase','state', ], 'integer','on' => [ 'default','update']],
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


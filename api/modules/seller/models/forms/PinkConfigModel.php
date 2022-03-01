<?php
namespace api\modules\seller\models\forms;

/**
 * This is the model class for table "{{%pink_config}}".
 *
 * @property int  $id  ;
 * @property int  $cid  拼团产品id;
 * @property int  $product_id productId 产品id;
 * @property int  $people  拼团总人数;
 * @property int  $start_time startTime 开始时间;
 * @property int  $end_time endTime 结束时间;
 * @property int  $remain_time remainTime 持续时间（秒）;
 * @property int  $created_at createdAt ;
 * @property int  $updated_at updatedAt ;
 */
class PinkConfigModel extends Base{
    public static function tableName()
    {
        return '{{%pink_config}}';
    }
    public $title;
    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['id'],'required','on' => [ 'update' ]],
            [[],'string','on' => [ 'default','update']],
            [['id','product_id','people','remain_time','status' ], 'integer','on' => [ 'default','update']],
            [['start_time','end_time', ], 'safe','on' => [ 'default','update']],

        ], $rules);
    }

    public function fields(){
        $fields = parent::fields();
        if (!empty($this->productInfo)) {
            $fields['productInfo'] = 'productInfo';
       }
        return $fields;
    }

    public function setAttributes($values, $safeOnly = true) {
        isset($values['start_time']) && $values['start_time'] = strtotime($values['start_time']);
        isset($values['end_time']) && $values['end_time'] = strtotime($values['end_time']);

        parent::setAttributes($values, $safeOnly);
    }

    public function getProductInfo()
    {
        return $this->hasOne(ProductModel::class, ['id' => 'product_id'])->select(['id', 'name', 'images', 'is_on_sale' ,  'sale_price', 'origin_price' ]);

    }

}


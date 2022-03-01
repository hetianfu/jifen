<?php
namespace api\modules\seller\models\forms;

/**
 * This is the model class for table "{{%product_sku}}".
 *
 * @property  $id  ;
 * @property string $bar_code barCode 条形码;
 * @property string $product_id productId 商品Id;
 * @property int  $origin_price originPrice ;
 * @property int  $cost_price costPrice 成本价;
 * @property int  $member_price memberPrice 会员价;
 * @property int  $sale_price salePrice 售价;
 * @property int  $is_sku isSku  是否为SKU: 0-否，1 是
 * @property string $shop_id shopId ;
 * @property string $tag_ids tagIds ;
 * @property string $spec_snap specSnap 标签镜像;
 * @property string $images  ;
 * @property int  $created_at createdAt ;
 * @property int  $updated_at updatedAt ;
 * @property mixed stock_number
 */
class ProductSkuModel extends Base{
    public $name;

    public static function tableName()
    {
        return '{{%product_sku}}';
    }

    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['id'],'required','on' => [ 'update' ]],
            [['bar_code', 'tag_ids', 'spec_snap','images'],'string','on' => [ 'default','update']],
            [['origin_price','cost_price','member_price','sale_price',  'first_shared','second_shared' ], 'number','on' => [ 'default','update']],

            [['stock_number', ], 'integer','on' => [ 'default','update']],
            [['product_id', ], 'safe','on' => [ 'default','update']],
        ], $rules);
    }


    public function fields(){
        $fields = parent::fields();

        isset($this->name)&&$fields['name']='name';
        isset($this->spec_snap)&&
        $fields['spec_snap']=function(){
            return json_decode($this->spec_snap, true);
        };
         return $fields;
    }

    public function setAttributes($values, $safeOnly = true) {
        parent::setAttributes($values, $safeOnly);
    }

}


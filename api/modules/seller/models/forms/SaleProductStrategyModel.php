<?php
namespace api\modules\seller\models\forms;
/**
 * This is the model class for table "{{%sale_product_strategy}}".
 *
 * @property string $id  MD5(商品Id+strategyId);
 * @property  $product_id productId 商品Id;
 * @property  $sku_id skuId 商品规格Id;
 * @property string $spec_snap specSnap 商品规格镜像;
 * @property int  $is_sku isSku 是否为SKU商品;
 * @property string $name  商品名称;
 * @property string $strategy_type strategyType 策略类型 SECKILL--秒杀;
 * @property  $sale_price salePrice 此活动售价价格;
 * @property int  $member_price memberPrice 此活动会员价;
 * @property int  $limit_number limitNumber 限购数量;
 * @property int  $parent_amount parentAmount 上家分销金额;
 * @property int  $final_stock finalStock 活动结束后的库存;
 * @property int  $stock  活动库存总数;
 * @property int  $sale_number saleNumber 当前售出数量;
 * @property int  $vir_sale_number virSaleNumber 虚拟销售数量;
 * @property int  $on_sale onSale 是否上架： 0 否 1是;
 * @property int  $created_at createdAt 创建时间;
 * @property int  $updated_at updatedAt 更新时间;
 */
class SaleProductStrategyModel extends Base{
    public static function tableName()
    {
        return '{{%sale_product_strategy}}';
    }

    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['id'],'required','on' => [ 'update' ]],
            [['id','product_id','spec_snap','name','strategy_type','bar_code'],'string','on' => [ 'default','update']],
            [['is_sku','member_price','limit_number','parent_amount','final_stock','stock_number','sale_number','vir_sale_number','on_sale', ], 'integer','on' => [ 'default','update']],
            [[ 'sale_price' ], 'number','on' => [ 'default','update']],

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


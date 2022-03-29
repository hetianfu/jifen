<?php
namespace api\modules\seller\models\forms;
/**
 * This is the model class for table "{{%product_stock}}".
 *
 * @property  $id  商品的skuId;
 * @property string $product_id productId 商品Id;
 * @property string $product_name productName 商品名称;
 * @property string $sku_tags skuTags sku标签;
 * @property int  $sku_stock skuStock 是否sku产生的库存 ,0不是   1 是;
 * @property string $shop_id shopId 店铺Id;
 * @property int  $total_number totalNumber 商品总数;
 * @property int  $warn_line warnLine 预警值;
 * @property int  $profit_stock profitStock 期初库存;
 * @property int  $input_stock inputStock 入库数量;
 * @property int  $refund_stock refundStock 退货入库数量;
 * @property int  $out_stock outStock 出库数量;
 * @property int  $wait_out_stock waitOutStock 待出库数量;
 * @property int  $wait_input_stock waitInputStock 待入库数量;
 * @property int  $stock_switch stockSwitch 0开启 1关闭;
 * @property string $center_stock centerStock 中央库存;
 * @property string $source_path sourcePath 平台商品：'PLATN',店铺商品：'SHOP';
 * @property  $create_time createTime ;
 * @property  $update_time updateTime ;
 */
class ProductStockModel extends Base{
    public static function tableName()
    {
        return '{{%product_stock}}';
    }

    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['id'],'required','on' => [ 'update' ]],
            [['product_id','product_name','sku_tags','shop_id','center_stock','source_path',],'string','on' => [ 'default','update']],
            [['sku_stock','total_number','warn_line','profit_stock','input_stock','refund_stock','out_stock','wait_out_stock','wait_input_stock','stock_switch', ], 'integer','on' => [ 'default','update']],
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


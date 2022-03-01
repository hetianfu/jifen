<?php
namespace api\modules\seller\models\forms;
/**
 * This is the model class for table "{{%product_stock_detail}}".
 *
 * @property  $id  ;
 * @property string $sku_id skuId ;
 * @property string $product_id productId 商品Id;
 * @property int  $sku_stock skuStock 是否sku产生的库存 0否， 1是;
 * @property string $product_name productName 商品名称;
 * @property string $merchant_id 商户Id ;
 * @property string $batch_number batchNumber 操作批次号;
 * @property string $source_path sourcePath 库存操作来源：收银端调用 CASHIER   系统后台调用SHOP;
 * @property string $order_id orderId ;
 * @property int  $sub_type subType  1入库2出库;
 * @property int  $type  0采购入库1调拔入库2盘点入库3退货入库4期初5生产归还入库6内部令用归还7借出归还8其它入库9调拔出库10销售出库11盘点出库12锁定13生产领料14内部领用15借用出库16其它出库17报废出库;
 * @property int  $left_number leftNumber 上次剩余数量;
 * @property  $stock_time stockTime 出入库时间;
 * @property int  $stock_number stockNumber 本次操作库存数量;
 * @property int  $cost_amount costAmount 入库合计成本金额;
 * @property int  $sales_amount salesAmount 出售合计金额;
 * @property string $order_no orderNo 关联订单Id;
 * @property string $operator  操作人;
 * @property string $remark  备注;
 * @property string $stock_json stockJson 库存镜像;
 * @property mixed spec_snap
 */
class ProductStockDetailModel extends Base{
    public static function tableName()
    {
        return '{{%product_stock_detail}}';
    }

    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['id'],'required','on' => [ 'update' ]],
            [['sku_id','product_id','spec_snap','product_name','merchant_id','batch_number','source_path','order_id','order_no','operator','remark','stock_json',],'string','on' => [ 'default','update']],
            [['sku_stock','sub_type','type','left_number','stock_number','cost_amount','sales_amount', ], 'integer','on' => [ 'default','update']],
        ], $rules);
    }


    public function fields(){
        $fields = parent::fields();
        isset($this->userSnap)&&  $fields['userSnap']='userSnap';
        return $fields;
    }

    public function setAttributes($values, $safeOnly = true) {

        parent::setAttributes($values, $safeOnly);
    }

    public function getUserSnap(){
        return $this->hasOne( UserInfoModel::class,['id'=>'operator'])->select(['id','nick_name','code','head_img','telephone' ]);
    }
}


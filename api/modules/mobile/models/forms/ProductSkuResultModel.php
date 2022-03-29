<?php
namespace api\modules\mobile\models\forms;
/**
 * This is the model class for table "{{%product_sku_result}}".
 *
 * @property  $id  商品Id;
 * @property string $merchant_id merchantId ;
 * @property string $tag_snap tagSnap 标签列表，包括名称，显示;
 * @property string $tag_detail_snap tagDetailSnap 标签详情，名称，价格，库存，缩略图，是否显示;
 * @property int  $created_at createdAt ;
 * @property int  $updated_at updatedAt ;
 */
class ProductSkuResultModel extends Base{

    public static function tableName()
    {
        return '{{%product_sku_result}}';
    }

    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['id'],'required','on' => [ 'update' ]],
            [[ 'merchant_id', 'tag_snap','tag_detail_snap', ],'string','on' => [ 'default','update']],
            [[ ], 'integer','on' => [ 'default','update']],
        ], $rules);
    }


    public function fields(){
        $fields = parent::fields();
         if(!empty(    $this->skuDetail)){
             $fields['skuDetail']='skuDetail';
         }
       if(!empty(    $this->strategySkuDetail)){
            $fields['skuDetail']='strategySkuDetail';
           unset($fields['strategySkuDetail']);
       }
            $fields['tagSnap']=function (){
            return json_decode($this->tag_snap);
        };
        $fields['tagDetailSnap']= function (){
            return json_decode($this->tag_detail_snap);
        };
        return $fields;
    }

    public function setAttributes($values, $safeOnly = true) {

        parent::setAttributes($values, $safeOnly);
    }


    public function  getSkuDetail(){
        return $this->hasMany( ProductSkuModel::class,['product_id'=>'id'])->select(['rf_product_sku.id','product_id','spec_snap','stock_number','sale_price','origin_price','member_price','images'])->orderBy(["rf_product_sku.sale_price"=>SORT_ASC]);
    }

    public function  getStrategySkuDetail(){

        return $this->hasMany( SaleProductStrategyModel::class,['product_id'=>'id'])->select(['rf_sale_product_strategy.id','product_id','spec_snap','stock_number','sale_price','origin_price','member_price','images']);
    }
}


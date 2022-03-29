<?php
namespace api\modules\mobile\models\forms;

/**
 * This is the model class for table "{{%user_shop_cart}}".
 *
 * @property string $id  ;
 * @property string $user_id userId ;
 * @property string $product_id productId 商品;
 * @property $sku_id skuId 商品SKU;
 * @property string $product_snap productSnap 商品镜像，名称----图片;
 * @property int  $number  数量;
 * @property  $created_at createdAt ;
 * @property  $updated_at updatedAt ;
 */
class UserShopCartModel extends Base{
    public static function tableName()
    {
        return '{{%user_shop_cart}}';
    }

    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['id'],'required','on' => [ 'update' ]],
            [['id','user_id','product_snap',],'string','on' => [ 'default','update']],
            [['product_id', ],'number','on' => [ 'default','update']],
            [['number', ], 'integer','on' => [ 'default','update']],
            [['sku_id', ], 'safe','on' => [ 'default','update']],
        ], $rules);
    }

    public function fields(){
        $fields = parent::fields();
       // $fields['productInfo']='productInfo';
        if(!empty($this->productInfo)){
            $fields['productInfo']='productInfo';
        }else{
           // throw new  FanYouHttpException(HttpErrorEnum::Expectation_Failed,ErrorProduct::NOT_SALE);
        }
     //   $fields['skuInfo']='skuInfo';
        if(!empty($this->skuInfo)){
            $fields['skuInfo']='skuInfo';
        }else{
          //  throw new  FanYouHttpException(HttpErrorEnum::Expectation_Failed,ErrorProduct::NO_SKU);
        }
        if (!empty($this->product_snap)) {
            $fields['productSnap'] = function () {
                return json_decode($this->product_snap, true);
            };
        }

        return $fields;
    }

    public function setAttributes($values, $safeOnly = true) {

        parent::setAttributes($values, $safeOnly);
    }
    public function getProductInfo( ) {
        return $this->hasOne( ProductModel::class,['id'=>'product_id'])->select(['id','is_on_sale','is_sku','name','cover_img','images' , ]);

    }
    public function getSkuInfo() {
        return $this->hasOne( ProductSkuModel::class,['id'=>'sku_id'])->select(['id','stock_number','spec_snap','sale_price' ,'origin_price','member_price','images' ]);

    }
}


<?php
namespace api\modules\mobile\models\forms;
use api\modules\seller\models\forms\ProductSkuModel;

/**
 * This is the model class for table "{{%product}}".
 *
 * @property  $id  商品Id;
 * @property string $category_id categoryId 分类id;
 * @property string $category_snap categorySnap 分类镜像;
 * @property string $source_path sourcePath 商品来源 平台PLANT --店铺SHOP;
 * @property string $merchant_id merchantId 商户Id;
 * @property string $merchant_name merchantName 商户名称;
 * @property string $name  商品名称;
 * @property int $product_score 商品积分;
 * @property string $sub_title subTitle 副标题;
 * @property string $short_title shortTitle 短标题;
 * @property string $type  商品类型（属性）:'REAL'- 实物商品 COUNTS”---计次商品;
 * @property string $level  平台推荐的级别--HOT代表热销;
 * @property string $city  城市;
 * @property int  $cost_price costPrice 成本价;
 * @property int  $origin_price originPrice 商品原价;
 * @property int  $sale_price salePrice 售价;
 * @property int  $member_price memberPrice 会员价;
 * @property string $bar_code barCode 商品条形码;
 * @property string $unit_id unitId 单位Id;
 * @property string $unit_snap unitSnap 单位镜像;
 * @property string $video_cover_img videoCoverImg 视频封面图;
 * @property string $images  商品图片（第一张为缩略图，其他为详情）;
 * @property string $video  商品视频URL地址;
 * @property string $share_img shareImg 商品分享图;
 * @property string $cover_img coverImg 商品封面图;
 * @property int  $base_sales_number baseSalesNumber 基础出售数量;
 * @property string $search_code searchCode Query商品搜索条件;
 * @property string $tips  商品样式;
 * @property int  $is_hot isStock 库存开启状态0不开启 1开启;
 * @property int  $is_on_sale isOnSale 是否上架 0否，1是
 * @property int  $is_sku isSku 是否Sku商品 0否，1是
 * @property int  $is_distribute 是否独立分销配置
 * @property int  $warn_line warnLine 库存报警线;
 * @property int  $auto_supply autoSupply  是否自动补 货;
 * @property int  $supply_number supplyNumber  每次补货数量;
 * @property int  $combo  0-否1是  是否为套餐;
 * @property int  $limit_number limitNumber 预留字段;
 * @property float  $weight  商品重量;
 * @property float volume;
 * @property int  $single_order_limit singleOrderLimit 每单限购数量;
 * @property string $combo_group comboGroup 当combo=1时有效,本productId,组成的组合商品列表集合;
 * @property string $sale_strategy saleStrategy 商品当前生效策略 秒杀--SECKILL 限量--NUMBER 限时--TIMER 促销--SALES,团购 GROUP_BUY;
 * @property  int support_score
 * @property  int support_coupon
 * @property  int is_freight
 * @property  int $command_id;
 * @property  int $is_good_refund;
 * @property  int freight_id;
 * @property int  $created_at
 * @property int  $updated_at
 * @property string $description  商品描述;
 * @property mixed real_sales_number
 * @property mixed|null need_score
 * @property mixed|null supply_name
 * @property mixed|null pay_type
 */
class ProductModel extends Base{
    public static function tableName()
    {
        return '{{%product}}';
    }
    public $sharedAmount;

    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['id'],'required','on' => [ 'update' ]],
            [['id','category_id','category_snap','source_path','merchant_id', 'supply_name',
              'merchant_name','name','sub_title','short_title','type','level','city','bar_code','unit_id','unit_snap','video_cover_img','video','share_img','cover_img','search_code','tips','combo_group','sale_strategy','description',],'string','on' => [ 'default','update']],

            [['is_distribute', 'support_coupon','support_score','is_freight','freight_id','special_flag','is_good_refund','command_id'],'integer','on' => [ 'default','update']],
            [['base_sales_number','real_sales_number','sales_number','is_hot','is_new','is_on_sale','is_sku','warn_line',
                'auto_supply','supply_number','combo','limit_number', 'single_order_limit','created_at','updated_at','product_score' ,'need_score','thumb_count','store_count'], 'integer','on' => [ 'default','update']],
            [['cost_price','origin_price','sale_price','member_price','weight','volume'  ],'number','on' => [ 'default','update']],
            [['images','distribute_config','good_rate','pay_type'],'safe','on' => [ 'default','update']],
        ], $rules);
    }
    public function fields(){
        $fields = parent::fields();
        isset($this->sharedAmount) && $fields['sharedAmount']='sharedAmount';
        isset($this->is_sku) && $fields['isSku']='is_sku';
        isset($this->is_freight) && $fields['isFreight']='is_freight';
        isset($this->is_distribute) && $fields['isDistribute']='is_distribute';
        isset($this->share_img) && $fields['shareImg']='share_img';

        isset($this->base_sales_number) && $fields['baseSalesNumber']='base_sales_number';
        isset($this->real_sales_number) && $fields['realSalesNumber']='real_sales_number';
        isset($this->category_id) && $fields['categoryId']='category_id';
        isset($this->shop_id) && $fields['shopId']='shop_id';
        isset($this->short_title) && $fields['shortTitle']='short_title';
        isset($this->sub_title) && $fields['subTitle']='sub_title';
        isset($this->sales_number) && $fields['salesNumber']='sales_number';
        isset($this->is_hot) && $fields['isHot']='is_hot';
        isset($this->is_on_sale) && $fields['isOnSale']='is_on_sale';
        isset($this->is_new) && $fields['isNew']='is_new';
        isset($this->sale_price) && $fields['salePrice']='sale_price';
        isset($this->member_price) && $fields['memberPrice']='member_price';
        isset($this->origin_price) && $fields['originPrice']='origin_price';
        isset($this->cover_img) && $fields['coverImg']='cover_img';
        isset($this->thumb_count) && $fields['thumbCount']='thumb_count';
        isset($this->sale_strategy) && $fields['saleStrategy']='sale_strategy';
        isset($this->stock_number) && $fields['stockNumber']='stock_number';

        isset($this->need_score) && $fields['needScore']='need_score';
        isset($this->store_count) && $fields['storeCount']='store_count';
        isset($this->support_score) && $fields['supportScore']='support_score';

        isset($this->video_cover_img) && $fields['videoCoverImg']='video_cover_img';
        isset($this->support_coupon) && $fields['supportCoupon']='support_coupon';
        isset($this->vir_content) &&
        $fields['virContent'] = function () {
            return json_decode($this->vir_content);
        };

        isset($this->unit_snap) &&
        $fields['unit_snap'] = function () {
            return json_decode($this->unit_snap);
        };
        $fields['images']=function (){
            return  json_decode($this->images);
        };
        isset($this->tips)&&
        $fields['tips']=function (){
            return  json_decode($this->tips);
        };
        isset($this->pay_type)&&
        $fields['payType']=function (){
            return  json_decode($this->pay_type);
        };
//        if (!empty($this->skuDetail)) {
//            $fields['skuList'] = 'skuDetail';
//            unset($fields['skuDetail']);
//        }

        unset(  $fields['base_sales_number'], $fields['real_sales_number'], $fields['sales_number'],
            $fields['category_id'], $fields['shop_id'],
            $fields['short_title'],$fields['is_hot'],$fields['is_new'],$fields['is_on_sale'],$fields['thumb_count'],
            $fields['vir_content'],   $fields['cover_img'],
            $fields['video_cover_img'],  $fields['support_coupon'],
            $fields['sale_strategy'], $fields['cost_price'], $fields['sale_price'],$fields['member_price'],$fields['origin_price'],$fields['stock_number'],$fields['sub_title'],
            $fields['need_score'],    $fields['store_count'], $fields['support_score'],$fields['share_img'],
            $fields['is_sku'],    $fields['is_distribute'], $fields['is_freight'],

        );
        return $fields;
    }

    public function setAttributes($values, $safeOnly = true) {

        parent::setAttributes($values, $safeOnly);
    }


    public function getSkuDetail()
    {
        return $this->hasMany(ProductSkuModel::class, ['product_id' => 'id'])->select([ 'product_id',  'stock_number', ])->orderBy(["rf_product_sku.sale_price"=>SORT_ASC]);

    }


}


<?php

namespace api\modules\seller\models\forms;

/**
 * This is the model class for table "{{%product}}".
 *
 * @property  $id  商品Id;
 * @property string $category_id categoryId 分类id;
 * @property string $category_snap categorySnap 分类镜像;
 * @property string $source_path sourcePath 商品来源 平台PLANT --店铺SHOP;
 * @property string $merchant_id merchantId  商户Id;
 * @property string $merchant_name merchantName 商户名称;
 * @property string $name  商品名称;
 * @property string $sub_title subTitle 副标题;
 * @property string $short_title shortTitle 短标题;
 * @property string $type  商品类型（属性）:'REAL'- 实物商品 COUNTS”---计次商品;
 * @property string $level  平台推荐的级别--HOT代表热销;
 * @property string $city  城市;
 * @property int $cost_price costPrice 成本价;
 * @property int $origin_price originPrice 商品原价;
 * @property int $sale_price salePrice 售价;
 * @property int $member_price memberPrice 会员价;
 * @property string $bar_code barCode 商品条形码;
 * @property string $unit_id unitId 单位Id;
 * @property string $unit_snap unitSnap 单位镜像;
 * @property string $video_cover_img videoCoverImg 视频封面图;
 * @property string $images  商品图片（第一张为缩略图，其他为详情）;
 * @property string $video  商品视频URL地址;
 * @property string $share_img shareImg 商品分享图;
 * @property string $cover_img coverImg 商品封面图;
 * @property int $base_sales_number baseSalesNumber 基础出售数量;
 * @property string $search_code searchCode Query商品搜索条件;
 * @property string $tips  商品样式;
 * @property int $is_hot isStock 库存开启状态0不开启 1开启;
 * @property int $is_on_sale isOnSale 是否上架 0否，1是
 * @property int $is_sku isSku 商品规格：0-单规格 1多规格
 * @property int $warn_line warnLine 库存报警线;
 * @property int $auto_supply autoSupply  是否自动补 货;
 * @property int $supply_number supplyNumber  每次补货数量;
 * @property int $combo  0-否1是  是否为套餐;
 * @property int $limit_number limitNumber 预留字段;
 * @property int $weight  商品重量;
 * @property int $single_order_limit singleOrderLimit 每单限购数量;
 * @property string $combo_group comboGroup 当combo=1时有效,本productId,组成的组合商品列表集合;
 * @property int $created_at
 * @property int $updated_at
 * @property string $description  商品描述;
 * @property mixed skuList
 * @property mixed productSpec
 * @property mixed sale_strategy
 * @property int product_score
 * @property mixed stock_number;
 * @property int real_sales_number
 * @property int sales_number
 * @property mixed $distribute_config
 * @property mixed|null need_score
 */
class ProductModel extends Base
{
    public $product_spec;
    public $sku_list;
    public $stock_number;
    public $category_list;

    public static function tableName()
    {
        return '{{%product}}';
    }

    public function rules()
    {
        $rules = parent::rules();
        return array_merge([

            [['id'], 'required', 'on' => ['update']],
            [['name', 'source_path', 'merchant_id', 'merchant_name', 'sub_title', 'short_title', 'type','supply_name',
                'level', 'city', 'bar_code', 'unit_id', 'video_cover_img', 'video', 'share_img', 'cover_img', 'search_code',
              'combo_group', 'sale_strategy', 'description','recommended','classification','rushed',
              'high_quality','hot_search'], 'string', 'on' => ['default', 'update']],

            [['is_distribute', 'base_sales_number', 'real_sales_number', 'sales_number', 'is_hot', 'is_on_sale', 'is_new',
                'is_sku', 'warn_line', 'auto_supply', 'supply_number', 'combo', 'limit_number',
                'single_order_limit', 'created_at', 'updated_at', 'need_score','product_score', 'show_order',
                'support_coupon','support_score','is_freight','freight_id',
                'thumb_count', 'store_count','special_flag','is_good_refund','command_id'], 'integer', 'on' => ['default', 'update']],
            [['cost_price', 'origin_price', 'sale_price', 'member_price','weight','volume'], 'number', 'on' => ['default', 'update']],
            [['id','sku_list', 'category_list', 'product_spec', 'category_id', 'images', 'tips', 'unit_snap','category_snap','distribute_config','good_rate','pay_type'], 'safe', 'on' => ['default', 'update']],
        ], $rules);
    }


    public function fields()
    {
        $fields = parent::fields();
        isset($this->stock_number) && $fields['stock_number'] = 'stock_number';

        isset($this->unit_snap) &&
        $fields['unit_snap'] = function () {
            return json_decode($this->unit_snap);
        };

        isset($this->images) &&
        $fields['images'] = function () {
            return json_decode($this->images);
        };
        isset($this->tips) &&
        $fields['tips'] = function () {
            return json_decode($this->tips);
        };
        isset($this->pay_type) &&
        $fields['payType'] = function () {
            return json_decode($this->pay_type);
        };

        if (!empty($this->skuDetail)) {
            $fields['skuList'] = 'skuDetail';

            unset($fields['skuDetail']);
        }

        if (!empty($this->category_id)) {
            $fields['category_id'] = function () {
                return json_decode($this->category_id);
            };
        }
        if (!empty($this->category_snap)) {
            $fields['category_snap'] = function () {
                return json_decode($this->category_snap);
            };
        }
        isset($this->distribute_config)&&
        $fields['distribute_config']=function(){
            return json_decode($this->distribute_config, true);
        };
        return $fields;
    }

    public function setAttributes($values, $safeOnly = true)
    {
        isset($values['unit_snap']) && $values['unit_snap'] = json_encode($values['unit_snap'],JSON_UNESCAPED_UNICODE);
        isset($values['images']) && $values['images'] = json_encode($values['images']);
        isset($values['tips']) && $values['tips'] = json_encode($values['tips'],JSON_UNESCAPED_UNICODE);
        isset($values['pay_type']) && $values['pay_type'] = json_encode($values['pay_type'],JSON_UNESCAPED_UNICODE);

        isset($values['distribute_config']) && $values['distribute_config'] = json_encode($values['distribute_config'],JSON_UNESCAPED_UNICODE);

        parent::setAttributes($values, $safeOnly);
    }

    public function getSkuDetail()
    {
        return $this->hasMany(ProductSkuModel::class, ['product_id' => 'id'])->select(['id', 'product_id', 'images', 'bar_code', 'spec_snap', 'stock_number', 'cost_price', 'sale_price', 'origin_price', 'member_price'])->orderBy(["rf_product_sku.sale_price"=>SORT_ASC]);

    }


}


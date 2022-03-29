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
class PayConfigModel extends Base
{
    public $product_spec;
    public $sku_list;
    public $stock_number;
    public $category_list;

    public static function tableName()
    {
        return '{{%pay_config}}';
    }

    public function rules()
    {
        $rules = parent::rules();
        return array_merge([

            [['id'], 'required', 'on' => ['update']],
            [['wx_app_id', 'wx_notify_url', 'wx_cert_path', 'wx_key_path', 'wx_key', 'ali_app_id', 'ali_merchant_private_key',
              'ali_charset', 'ali_sign_type', 'ali_alipay_public_key','name','mch_id'], 'string', 'on' => ['default', 'update']],
            [['status'], 'integer', 'on' => ['default', 'update']],

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


<?php
namespace api\modules\seller\models\forms;
/**
 * This is the model class for table "{{%sale_product}}".
 *
 * @property  $id  ;
 * @property string $name  商品名称;
 * @property string $product_snap productSnap 商品镜像;
 * @property string $strategy_type strategyType 活动类型---
        * 秒杀--SECKILL
        * 限量--NUMBER
        * 限时--TIMER
        * 促销--SALES;
 * @property int  $status  状态 ，0生效，1失效;
 * @property int  $on_show onShow 0下架，1上架;
 * @property int  $origin_price originPrice 商品原价;
 * @property int  $sale_price salePrice 售价;
 * @property int  $member_price memberPrice 会员价;
 * @property string $start_date startDate 开始时间;
 * @property string $end_date endDate 结束时间;
 * @property int  $start_hour startHour 开始时间（小时）;
 * @property int  $end_hour endHour 结束时间（小时）;
 * @property int  $created_at createdAt ;
 * @property int  $updated_at updatedAt ;
 * @property string cover_img  封面图
 * @property mixed images
 */
class SaleProductModel extends Base{


    public static function tableName()
    {
        return '{{%sale_product}}';
    }
    public function rules(){
        $rules = parent::rules();
        return array_merge([

            [['name','strategy_type','start_date','end_date','strategy_type' ],'string','on' => [ 'default','update']],
            [['status','on_show','start_hour','end_hour','show_order','limit_number' ], 'integer','on' => [ 'default','update']],
            [['id',  ], 'safe','on' => [ 'default','update']],
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

        parent::setAttributes($values, $safeOnly);
    }
    public function getProductInfo()
    {
        return $this->hasOne(ProductModel::class, ['id' => 'id'])->select(['id', 'name', 'images', 'is_on_sale',  'sale_price', 'origin_price' ]);

    }

}


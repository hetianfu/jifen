<?php

namespace api\modules\mobile\models;

use yii\base\Model;

/**
 * This is the model class for table "{{%user_shop_cart}}".
 *
 * @property string $id  ;
 * @property string $salesRecord 购买记录;
 * @property string $barCode 商品条形码;
 * @property string $categoryId 分类Id;
 * @property int $combo 是否组合商品;
 * @property  $costPrice 成本价 ;
 * @property  $buyDescription 购买须知 ;
 * @property $description 商品描述
 * @property $images 轮播图
 * @property $memberPrice 轮播图
 */
class ProductResult extends Model
{
    public $id;
    public $salesRecord;
    public $barCode;
    public $salesNumber;
    public $categoryId;
    public $combo;
    public $costPrice;
    public $buyDescription;
    public $description;
    public $images;
    public $tips;

    public $name;
    public $memberPrice;
    public $originPrice;
    public $salePrice;
    public $shareAmount;
    public $shortTitle;
    public $showOrder;
    public $type;
    public $unit_id;
    public $unit_snap;

    public $effect_time;
    public $expireTime;
    public $subTitle;
    public $remaining_second;
    public $salesReport;
    public $sales;
    public $isSku;
    public $isOnSale;
    public $coverImg;

    public $shareImg;

    public $videoCoverImg;
    public $video;
    public $stockNumber;

    public $strategy;
    public $skuDetail;
    public $tagSnap;

    public $storeCount;

    public $strategyType;


    public $saleStrategy;
    public $baseSalesNumber;
    public $realSalesNumber;
    public $pinkInfo;
    public $pinkConfig;

    public $sharedAmount;

    public $needScore;
    public $skuList;

    public $goodRate;
    public function fields()
    {
        $fields = parent::fields();
        isset($this->tips)&&
        $fields['tips']=function (){
            return  json_decode($this->tips);
        };

        isset($this->categoryId)&&
        $fields['categoryId']=function (){
            return  json_decode($this->categoryId);
        };

        isset($this->images)&&
        $fields['images']=function (){
            return  json_decode($this->images);
        };
        return $fields;
    }

}


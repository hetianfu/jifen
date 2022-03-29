<?php

namespace api\modules\mobile\models\request;

use yii\base\Model;

/**
 * 当前订单商品的价格
 * Class ProductOrderSingle
 * @property string saleStrategy
 * @property int isGoodRefund
 * @property mixed|null needScore
 * @package api\modules\mobile\models\request
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-07-10 10:44
 */
class ProductOrderSingle extends Model
{
    public function __construct($values,$safeOnly=false)
    {
        parent::setAttributes($values, $safeOnly);
    }

    public $id;
    //购物车Id
    public $cartId;

    public $stockId;

    public $number;
    public $skuId;

    public $name;
    public $coverImg;
    public $images;
    public $amount;

    public $costAmount;
    public $originPrice;
    public $salePrice;
    public $stockNumber;
    //活动策略
    public $strategyType;
    public $saleStrategy;
    public $specSnap;


    public $isGoodRefund;
    public $commandId;
    public $isDistribute;

    public $productFreight;

    public $needScore;

    public $supplyName;
    public function fields()
    {
        $fields = parent::fields();
        $fields['images']= function (){
            return json_decode($this->images);
        };
        return $fields;

    }

}
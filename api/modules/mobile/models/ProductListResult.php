<?php
namespace api\modules\mobile\models;
use yii\base\Model;

class ProductListResult extends Model{
    public $id;
    public $salesRecord;
    public $barCode;
    public $baseSalesNumber;
    public $categoryId;
    public $combo;
    public $costPrice;

    public $name;
    public $memberPrice;
    public $originPrice;
    public $salePrice;
    public $shareAmount;
    public $shortTitle;
    public $showOrder;
    public $type;
    public $unitId;
    public $effectTime;
    public $expireTime;
    public $subTitle;
    public $remainingSecond;
    public $salesReport;
    public $sales;
    public $sku;
    public $shareImg;
    public $unit;


    public function fields(){
        $fields=parent::fields();

        unset(  $fields['productId'],  $fields['productSnap'],  $fields['number']);
        return $fields;
    }

}


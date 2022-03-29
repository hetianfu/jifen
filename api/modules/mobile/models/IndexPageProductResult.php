<?php
namespace api\modules\mobile\models;
use fanyou\enums\StatusEnum;
use yii\base\Model;

class IndexPageProductResult extends Model{
    public $id;
    public $name;
    public $pic;
    public $url;
    public $strategyType;
    public $coverImg;
    public $salePrice;
    public $originPrice;
    public $salesNumber;
    public $effectTime;
    public $expireTime;
    public $status;

    public $stockNumber;
    public $startSeconds;
    public $endSeconds;
    public function fields(){
        $fields=parent::fields();


        $fields['pic']='coverImg';
        unset($fields['coverImg']);
        return $fields;
    }

}


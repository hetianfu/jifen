<?php

namespace api\modules\seller\models\forms;

use fanyou\enums\SortEnum;
use yii\base\Model;

class BaseQuery extends Model
{
    public $id;
    public $merchant_id;
    public $shop_id;
    public $page;
    public $limit=10;
    public $orders;

    public $gt_created_at;
    public $lt_created_at;

    public $ge_created_at;
    public $le_created_at;

    public $gt_updated_at;
    public $le_updated_at;
    public function rules()
    {
        $rules = parent::rules();
        return array_merge([
            [['id','shop_id','merchant_id'], 'string'],
            [['page','limit'], 'integer'],
            [['orders', 'gt_created_at','lt_created_at', 'ge_created_at','le_created_at',
                'gt_updated_at','le_updated_at'], 'safe'],
        ], $rules);
    }

    public function fields()
    {
        $fields =[];// parent::fields();
        isset($this->id) && $fields['id'] = 'id';
        isset($this->merchant_id) && $fields['merchant_id'] = 'merchant_id';
        isset($this->gt_created_at)&&$fields['gt_created_at']=function (){
            return   strtotime($this->gt_created_at) ;
        } ;
        isset($this->lt_created_at)&&$fields['lt_created_at']=function (){
            return   strtotime($this->lt_created_at);
        } ;
        isset($this->ge_created_at)&&$fields['ge_created_at']=function (){
            return   strtotime($this->ge_created_at) ;
        } ;
        isset($this->le_created_at)&&$fields['le_created_at']=function (){
            return   strtotime($this->le_created_at);
        } ;
        isset($this->gt_updated_at)&&$fields['gt_updated_at']=function (){
            return   strtotime($this->gt_updated_at);
        } ;
        isset($this->le_updated_at)&&$fields['le_updated_at']=function (){
            return   strtotime($this->le_updated_at);
        } ;
        $fields['page'] = 'page';
        $fields['limit'] = 'limit';
        unset( $fields['page'],$fields['limit']);
        return $fields;
    }

    public function extraFields()
    {
        $extraFields = parent::extraFields();
        $extraFields['limit'] = function () {
            return empty($this->limit) ? 10 : $this->limit;
        };
        $extraFields['page'] = function () {
            return empty($this->page) ? 1 : $this->page;
        };
        return $extraFields;
    }
    public function  getLimit() {

        return empty($this->limit) ? 10 : $this->limit;
    }

    public function  getOrdersArray($default=SortEnum::CREATED_AT,$sort=SORT_DESC,
                                    $time=SortEnum::CREATED_AT,$timeSort=SORT_DESC ) {
        if(empty($this->orders)){
           return [$default=>$sort,$time=>$timeSort];
        }

        $ordersArray = json_decode($this->orders, true);
        foreach ($ordersArray as $k=>$v){
            if($v===SortEnum::ASC){
                $ordersArray[$k]=SORT_ASC;
            }
            else if ($v===SortEnum::DESC){
                $ordersArray[$k]=SORT_DESC;
            }
        }
        return $ordersArray;
    }
    public function  productOrdersArray($default=SortEnum::CREATED_AT,$sort=SORT_DESC,
                                      $id='id',$idSort=SORT_ASC) {
        if(empty($this->orders)){
            return [$default=>$sort,$id=>$idSort];
        }

        $ordersArray = json_decode($this->orders, true);
        foreach ($ordersArray as $k=>$v){
            if($v===SortEnum::ASC){
                $ordersArray[$k]=SORT_ASC;
            }
            else if ($v===SortEnum::DESC){
                $ordersArray[$k]=SORT_DESC;
            }
        }
        return $ordersArray;
    }
}

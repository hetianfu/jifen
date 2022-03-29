<?php

namespace api\modules\manage\models\forms;
use fanyou\enums\SortEnum;
use yii\base\Model;
class BaseQuery extends Model
{
    public $id;
    public $page;
    public $limit;
    public $orders;
    public $merchant_id;
    public $shop_id;

    public $gt_created_at;
    public $lt_created_at;

    public $ge_created_at;
    public $le_created_at;
    public function rules()
    {
        $rules = parent::rules();
        return array_merge([
            [['id','shop_id','merchant_id'], 'string'],
            [['orders', 'gt_created_at','lt_created_at', 'ge_created_at','le_created_at'], 'safe'],
            [['page','limit'], 'integer'],
        ], $rules);
    }

    public function fields()
    {
        $fields =[];// parent::fields();
       /// isset($this->id) && $fields['id'] = 'id';
        isset($this->shop_id) && $fields['shop_id'] = 'shop_id';
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

        $fields['page'] = 'page';
        $fields['limit'] = 'limit';

        unset( $fields['page'],$fields['limit'], $fields['orders']);
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

    public function  getOrdersArray($default='id',$sort=SORT_DESC,
                                    $time=SortEnum::CREATED_AT,$timeSort=SORT_DESC ) {
        if(!empty($default)){
            return [$default=>$sort,$time=>$timeSort];
        }
        if(empty($this->orders)){
            return [SortEnum::CREATED_AT=>SORT_DESC];
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

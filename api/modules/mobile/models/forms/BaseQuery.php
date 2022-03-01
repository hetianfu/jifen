<?php

namespace api\modules\mobile\models\forms;
use fanyou\enums\SortEnum;
use yii\base\Model;
class BaseQuery extends Model
{
    public $id;
    public $page;
    public $user_id;
    public $limit=10;
    public $orders;

    public $created_at;
    public function rules()
    {
        $rules = parent::rules();
        return array_merge([
            [['id','user_id'], 'string'],
            [['orders', 'created_at'], 'safe'],
            [['page','limit'], 'integer'],
        ], $rules);
    }
    public function fields()
    {
        $fields =[];
        $fields['page'] = 'page';
        $fields['limit'] = 'limit';

        isset($this->user_id)&&$fields['user_id']='user_id';
        isset($this->created_at)&&$fields['created_at']='created_at';
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

    public function  getOrdersArray($default=SortEnum::CREATED_AT,$sort=SORT_ASC,
                                    $time=SortEnum::CREATED_AT,$timeSort=SORT_DESC  ) {
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

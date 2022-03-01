<?php
namespace api\modules\seller\models\forms;

use fanyou\enums\entity\OrderStatusEnum;

/**
 * @property int id
 * @author E-mail: Administrator@qq.com
 * Create Time: 2019/03/27
 */
class BasicOrderInfoQuery extends BaseQuery
{
    public $name;
    public $user_id;
    public $status;
    public $type;
    public $pay_type;
    public $search_word;
    public $distribute;

    public $product_id;
    public $product_name;
    public $source_id;
    public $supply_name;
    public function rules()
    {
        $rules = parent::rules();
        return array_merge([
            [['user_id','name','status','type','pay_type','search_word','supply_name' ], 'string'],
            [['distribute' ,'source_id'], 'integer'],
            [['product_id','product_name' ], 'safe'],
        ], $rules);
    }
    public function fields()
    {
        $fields = parent::fields();
        if(isset($this->status)){
            if($this->status==OrderStatusEnum::ALL_EFFECT_STATUS){
                $fields['status']=function (){
                    return OrderStatusEnum::EFFECT_ALL;
                };
            }else{
                $fields['status']= 'status';
            }
        }

        isset($this->supply_name) && $fields['supply_name'] =  'supply_name';
        isset($this->type)&&$fields['type']='type';
        isset($this->pay_type)&&$fields['pay_type']='pay_type';
        isset($this->user_id)&&$fields['user_id']='user_id';
        isset($this->search_word)&&$fields['search_word']='search_word';
        isset($this->distribute)&&$fields['distribute']='distribute';
        isset($this->product_id)&&$fields['product_id']='product_id';
        isset($this->product_name)&&$fields['cart_snap']='product_name';
        isset($this->source_id)&&$fields['source_id']='source_id';
        return $fields;
    }

}



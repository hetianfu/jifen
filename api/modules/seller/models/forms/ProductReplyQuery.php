<?php
namespace api\modules\seller\models\forms;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-05-27
 */

class ProductReplyQuery  extends BaseQuery{
     public  $name;
    public  $order_id;
     public  $is_del;
     public  $user_id;
     public  $product_id;
     public  $is_recommend;
	 public  $status;
     public function rules(){
         $rules = parent::rules();
         return array_merge([
                [['name' ,'user_id','product_id','order_id'], 'string'],
             [['is_del','is_recommend','status' ], 'integer'],
            ], $rules);
         }
    public function fields(){
        $fields = parent::fields();
        isset($this->user_id)&&$fields['user_id']='user_id';
        isset($this->order_id)&&$fields['order_id']='order_id';
        isset($this->product_id)&&$fields['product_id']='product_id';

        isset($this->is_del)&&$fields['is_del']='is_del';
        isset($this->is_recommend)&&$fields['is_recommend']='is_recommend';
		isset($this->status)&&$fields['status']='status';
        return $fields;
    }
}
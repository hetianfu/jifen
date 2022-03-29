<?php
namespace api\modules\mobile\models\forms;

/**
 * @property int is_recommend
 * @property int is_del
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-05-27
 */

class ProductReplyQuery  extends BaseQuery{
     public  $name;
     public  $orderId;
     public  $userId;
     public  $productId;
     public  $isRecommend=1;
	 public  $status=1;
     public  $isDel=0;
     public function rules(){
         $rules = parent::rules();
         return array_merge([
             [['name' ], 'string'],
             [['userId','productId','orderId' ], 'string'],
             [['isDel','isRecommend' ,'status'], 'integer'],
            ], $rules);
         }
    public function fields(){
        $fields = parent::fields();
        isset($this->orderId)&&$fields['order_id']='orderId';
        isset($this->userId)&&$fields['user_id']='userId';
        isset($this->productId)&&$fields['product_id']='productId';

        isset($this->isRecommend)&&$fields['is_recommend']='isRecommend';
        isset($this->isDel)&&$fields['is_del']='isDel';
		isset($this->status)&&$fields['status']='status';
		unset($fields['isDel'],$fields['isRecommend'],$fields['orderId'],$fields['userId'],$fields['productId']);
        return $fields;
    }
}
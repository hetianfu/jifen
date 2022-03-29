<?php
namespace api\modules\mobile\models\forms;
/**
 * This is the model class for table "{{%coupon}}".
 *
 * @property  $post_id  快递ID ;

 */
class OrderPayProduct extends Base{
    public $id;
    public $skuId;
    public $categoryId;
    public $coverImg;
    public $price;
    public $originPrice;
    public $number;
    public $amount;
    public $originAmount;
    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['skuId','id','name','coverImg','categoryId'],'string','on' => [ 'default','update']],
            [['amount','originAmount','price','number', ], 'number','on' => [ 'default','update']],
        ], $rules);
    }


    public function fields(){
        $fields = parent::fields();

        unset(  $fields['postId']);
        return $fields;
    }

    public function setAttributes($values, $safeOnly = true) {
        $values['amount']=$values['price']*$values['number'];
        $values['originAmount']=$values['originPrice']*$values['number'];
        $values['categoryId']=$values['categoryId'];

       // $values['isLimit']&&$values['is_limit']=$values['isLimit'];
        parent::setAttributes($values, $safeOnly);
    }
}


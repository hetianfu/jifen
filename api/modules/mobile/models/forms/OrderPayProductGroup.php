<?php
namespace api\modules\mobile\models\forms;
use yii\base\Model;

/**
 * This is the model class for table "{{%coupon}}".
 *
 * @property  $post_id  快递ID ;
 * @property float|int productScore
 */
class OrderPayProductGroup extends Model {
    public $payCategoryIds;
    public $payProductIds;

    public $cartIds;
    public $productAmount;
    public $originAmount;

    public $discountAmount;

   public $productList;
   public $productScore;

    public $merchantId;

    //是否为虚拟商品
    public $isVirtual;
    public $productFreight;

    public $forbidScoreAmount;
    public $forbidCouponAmount;

    public $needScore;
    public $supplyName;
    public $payType;
    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['productAmount','originAmount','originPrice' ,'discountAmount' ,'productScore','productFreight','forbidScoreAmount','forbidCouponAmount'], 'number','on' => [ 'default','update']],
            [['productList','cartIds' ,'merchantId','payCategoryIds','payProductIds', ], 'safe','on' => [ 'default','update']],

        ], $rules);
    }


    public function fields(){
        $fields = parent::fields();


        return $fields;
    }

    public function setAttributes($values, $safeOnly = true) {

        parent::setAttributes($values, $safeOnly);
    }
}


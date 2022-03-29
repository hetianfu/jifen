<?php
namespace api\modules\mobile\models;
use yii\base\Model;

/**
 * This is the model class for table "{{%basic_order_info}}".
 *
 * @property  discountAmount
 * @property  isVip
 */
class CalculateOrderInfoResult extends Model {
    public $id;
    public $productAmount;
    public $originAmount;
    public $payAmount;
    public $discountAmount;
    public $freightId;
    public $freightAmount=0;
    public $productList;// id,skuId,number
    public $couponList;
    public $userId;
    public $productScore;
    public $deductScore;
    public $canDeductScore;

    public $deductScoreAmount;
    public $couponAmount;
    public $scoreAmount;
    public $amount;

    public  $distribute;

    public $cartIds;

    public $merchantId;

    public $userCouponId;
    public $isVip=0;

    public $isVirtual;
    public $productFreight=0;
    public $orderProductType;

    public $remark;

    public $needScore;
    public $supplyName;
    public $payType;
    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [[ 'id','status', 'userId', ],'string','on' => [ 'default','update']],

            [['amount', 'originAmount','productAmount','freightAmount','scoreAmount',  'discountAmount','deductScoreAmount','productScore','deductScore','isVip'], 'number','on' => [ 'default','update']],
            [['createdAt', 'updatedAt','productList','couponList','cartIds','merchantId','distribute'],'safe','on' => [ 'default','update']],
        //'user_coupon_id',
        ], $rules);
    }

    public function fields(){
        $fields = parent::fields();

        return $fields;
    }

}


<?php

namespace api\modules\mobile\models\request;

use yii\base\Model;

/**
 * This is the model class for table "{{%basic_order_info}}".
 *
 * @property  discountAmount
 */
class CalculateOrderInfoRequest extends Model
{
    public $id;
    /**
     * 是否自提
     * @var
     */
    public $distribute;
    public $userId;
    /**
     * 优惠券Id
     * @var
     */
    public $userCouponId;
    /**
     * 优惠券面值
     * @var
     */
    public $couponDiscount = 0;
    /**
     * 积分抵扣金额
     * @var
     */
    public $scoreDiscount = 0;

    public $productList;

    public $userTelephone;

    public $userSnap;
    public $addressSnap;

    public $cooperateShopId;
    public $cooperateShopAddress;
    public $payType;
    //public $isVip;

    public $connectSnap;
    public $cityCode;
    public $remark;
    public $isPink;
    public $pinkId;

    public $fullPay;

    public $appointTime;

    public $extendSnap;
    /**
     * @var mixed
     */
    public $sourceId;
    public $phoneMode;
    public function fields()
    {
        $fields = parent::fields();
        isset($this->addressSnap) && $fields['addressSnap'] = 'addressSnap';
        isset($this->cooperateShopAddress) && $fields['cooperateShopAddress'] = 'cooperateShopAddress';
        isset($this->connectSnap) && $fields['connectSnap'] = 'connectSnap';
        return $fields;
    }

    public function setAttributes($values, $safeOnly = true)
    {
        parent::setAttributes($values, $safeOnly);
    }
}


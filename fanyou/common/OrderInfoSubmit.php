<?php

namespace fanyou\common;

use yii\base\Model;

/**
 * 提交订单
 * Class OrderInfoSubmit
 * @package fanyou\common
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-06-10 15:00
 */
class OrderInfoSubmit extends Model
{
    /**
     * 订单Id
     * @var
     */
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
    public $couponDiscount=0;

    /**
     * 积分抵扣金额
     * @var
     */
    public $scoreDiscount=0;

    public $productList;

    public $userSnap;
    public $addressSnap;

    public function fields(){
        $fields=parent::fields();

        return $fields;
    }

}
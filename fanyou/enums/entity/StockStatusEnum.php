<?php

namespace fanyou\enums\entity;

/**
 * Class StockStatusEnum
 * @package fanyou\enums\entity
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-06-22 10:02
 */
class StockStatusEnum
{

    const BUY_IN = 0;//采购入库
    const MOVE_IN = 1;//调拔入库
    const CHECK_IN = 2;//盘点入库
    const REFUND_IN =3;//退货入库
    const INIT = 4;//期初

    const USE_BACK_IN =  5;//生产归还入库
    const INNER_MOVE_BACK_IN = 6;//内部调用归还
    const BORROW_BACK_IN = 7;//借出归还
    const OTHER_IN = 8;//其它入库


    const MOVE_OUT = 9;//调拔出库

    const SALE_OUT = 10;//销售出库
    const CHECK_OUT = 11;//盘点出库

    const LOCK = 12;//锁定

    const USE_OUT= 13;//生产领料
    const INNER_MOVE_OUT= 14;//内部领用

    const BORROW_OUT= 15;//借用出库
    const OTHER_OUT= 16;// 其它出库
    const DESTROY_OUT= 17;// 17报废出库


    const ORDER_CANCEL_IN= 18;// 订单取消入库
}
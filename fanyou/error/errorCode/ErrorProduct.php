<?php


namespace fanyou\error\errorCode;

/**
 * Class ErrorProduct
 * @package fanyou\error\errorCode
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-06-19 9:59
 */
class ErrorProduct
{
    const NAME_REPEAT = '商品名重复';
    const UN_EXISTS = '商品不存在';
    const REPEAT = '商品已存在';
    const NOT_SALE = '商品已下架';

    const NO_SKU = '商品属性异常';

    const STOCK_NUN_ENOUGH = '库存不足';

    const ERROR_SKU_PARAMS = '多规格商品必须设置sku属性';

    const PRODUCT_HAS_IN_CART = '商品已经在购物车中';
    const SYSTEM_ERROR = '服务器开小差';


    const EMPTY_PRODUCT = '商品数据为空';

    const UN_EFFECT_STRATEGY = '当前策略失效';

    const STRATEGY_LIMIT_NUN = '秒杀限量';
}
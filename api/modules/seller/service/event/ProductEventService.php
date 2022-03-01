<?php

namespace api\modules\seller\service\event;

use api\modules\mobile\models\forms\UserShopCartModel;
use api\modules\seller\models\event\ProductEvent;
use api\modules\seller\models\forms\ProductCategoryModel;
use api\modules\seller\models\forms\ProductSkuModel;
use api\modules\seller\models\forms\ProductSkuResultModel;
use api\modules\seller\service\BasicService;
use Yii;

/**
 * Class ProductEventService
 * @package api\modules\seller\service\event
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-06-10 10:42
 */
class ProductEventService extends BasicService
{
    /**
     * @param ProductEvent $event
     * @throws \yii\db\Exception
     */
    public static  function saveProduct(ProductEvent  $event)
    {  $categoryIds=$event->categoryIdList;
        if(empty($categoryIds)){
            return ;
        }
        $productId=$event->productId;
        ProductCategoryModel::deleteAll(['product_id'=>$productId]);

        $field = [ 'product_id', 'category_id' ,'created_at','updated_at'];
        $rows=[];
        $now=time();
        if(!empty($categoryIds)){
        foreach ($categoryIds as $k=>$value){
            $v['product_id']=$productId;
            $v['category_id']=$value ;
            $v['created_at']=$now;
            $v['updated_at']=$now;
            ksort($v);
            $rows[]=$v;
        }
        // 批量写入数据
        sort($field);
        !empty($rows) && Yii::$app->db->createCommand()->batchInsert(ProductCategoryModel::tableName(), $field, $rows)->execute();
        }
    }

    /**
     * @param ProductEvent $event
     */
    public static  function deleteProduct(ProductEvent  $event){
        ProductCategoryModel::deleteAll(['product_id'=>$event->productId]);
        ProductSkuModel::deleteAll(['product_id'=>$event->productId]);
        ProductSkuResultModel::deleteAll(['id'=>$event->productId]);
        UserShopCartModel::deleteAll(["product_id"=>$event->productId]);
    }
}
/**********************End Of Coupon 服务层************************************/


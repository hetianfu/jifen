<?php

namespace api\modules\seller\service\event;

use api\modules\seller\models\event\CategoryEvent;
use api\modules\seller\models\forms\ProductCategoryModel;
use api\modules\seller\models\forms\ProductModel;
use api\modules\seller\service\BasicService;
use Yii;

/**
 * Class CategoryEventService
 * @package api\modules\seller\service\event
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-06-10 10:42
 */
class CategoryEventService extends BasicService
{
    /**
     * @param CategoryEvent $event
     */
    public static  function saveCategory(CategoryEvent  $event)
    {
        $categoryId=$event->categoryId;
        $updateName=$event->updateName;
        $categoryName=$event->categoryName;
        $pcList=ProductCategoryModel::find()->where(['category_id'=>$categoryId])->all();
        if(!empty($pcList)){
            foreach ($pcList as $k=>$pc){
                $product=ProductModel::findOne(['id' => $pc['id']]) ;
                if(!empty($product)){
                  //  $categoryId= json_decode($product->category_id)  ;
                    $categorySnap=str_replace($categoryName,$updateName,$product->category_snap ) ;
                    ProductModel::updateAll(['category_snap'=>$categorySnap],['id'=>$product->id]) ;
                }
            }
        }
    }


}
/**********************End Of Coupon 服务层************************************/


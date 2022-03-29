<?php

namespace api\modules\mobile\controllers;

use api\modules\auth\ApiAuth;
use api\modules\mobile\models\forms\UserProductModel;
use fanyou\tools\ArrayHelper;
use fanyou\tools\StringHelper;
use Yii;

/**
 * Information
 * @author E-mail: Administrator@qq.com
 *
 */
class CommonController extends BaseController
{

    public function init()
    {
        parent::init();
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => ApiAuth::class,
            'optional' => ['get-currency-time', 'get-roll-buy-record']
        ];
        return $behaviors;
    }
    /*********************Information模块控制层************************************/


    /**
     * 获取系统当前时间
     * @return mixed
     */
    public function actionGetCurrencyTime()
    {
        return time();

    }

    /**
     * 购买记录滚动
     * @return mixed
     */
    public function actionGetRollBuyRecord()
    {
        $productId = parent::getRequestId("productId");
        if(empty($productId)){
            $productId='';
        }
        $tokenId = "html_get-roll-buy-record". $productId;
        $tokenContent = Yii::$app->cache->get($tokenId);
        if (!empty($tokenContent)) {
            return json_decode($tokenContent);
        }
        if (empty($productId)) {
            $array = StringHelper::toCamelize(ArrayHelper::toArray(UserProductModel::find()->limit(50)->orderBy(['created_at' => SORT_DESC])->all()));
        } else {
            $array = StringHelper::toCamelize(ArrayHelper::toArray(UserProductModel::find()->where(['product_id' => $productId])->limit(50)->orderBy(['created_at' => SORT_DESC])->all()));
        }
        Yii::$app->cache->set($tokenId, json_encode($array), 120);
        return $array;

    }

}
/**********************End Of Information 控制层************************************/ 



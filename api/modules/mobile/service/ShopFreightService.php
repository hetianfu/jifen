<?php

namespace api\modules\mobile\service;

use fanyou\enums\entity\FreightConfigEnum;
use fanyou\enums\NumberEnum;
use fanyou\enums\SystemConfigEnum;
use fanyou\tools\SystemConfigHelper;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-05-14
 */
class ShopFreightService
{

/*********************ShopFreight模块服务层************************************/

    /**
     * 获取邮费配置
     * @param $amount
     * @param int $distribute
     * @return int
     * @throws \yii\web\NotFoundHttpException
     */
    public function getFreightAmount($amount,$distribute=NumberEnum::ZERO):float
    {
        if(empty($distribute)){
            $freight=  new SystemConfigHelper(SystemConfigEnum::FREIGHT_CONFIG) ;
            $model= $freight->getConfigValue();
            $postAmount=$model[FreightConfigEnum::FREIGHT_AMOUNT];
            $freeLine=$model[FreightConfigEnum::FREIGHT_FREE_LINE];
            if(empty($freeLine) ){
                return empty($postAmount)?NumberEnum::ZERO:$postAmount;
            }else if(($freeLine-$amount)>NumberEnum::ZERO){
                return empty($postAmount)?NumberEnum::ZERO:$postAmount;
            }
        }
        return NumberEnum::ZERO;
    }

}
/**********************End Of ShopFreight 服务层************************************/


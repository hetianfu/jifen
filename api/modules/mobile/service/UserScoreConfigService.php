<?php

namespace api\modules\mobile\service;

use fanyou\enums\entity\ScoreConfigEnum;
use fanyou\enums\SystemConfigEnum;
use fanyou\tools\SystemConfigHelper;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-05-11
 */
class UserScoreConfigService
{

/*********************UserScoreConfig模块服务层************************************/

    public function getScoreConfig()
    {
        $freight=  new SystemConfigHelper(SystemConfigEnum::SCORE_CONFIG) ;
        $model= $freight->getConfigValue();
        return $model;
    }
    /**
     * 获取积分抵扣比例
     * @return float|null
     * @throws \yii\web\NotFoundHttpException
     */
    public function getConfigDeduct():?float
    {
        $freight=  new SystemConfigHelper(SystemConfigEnum::SCORE_CONFIG) ;
        $model= $freight->getConfigValue();
        $deduct=$model[ScoreConfigEnum::SCORE_DEDUCT];
        return $deduct;
    }
}
/**********************End Of UserScoreConfig 服务层************************************/


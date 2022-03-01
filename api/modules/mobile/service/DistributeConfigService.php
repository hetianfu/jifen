<?php

namespace api\modules\mobile\service;

use fanyou\enums\SystemConfigEnum;
use fanyou\tools\SystemConfigHelper;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-05-22
 */
class DistributeConfigService
{

/*********************DistributeConfig模块服务层************************************/

    public function getDistributeConfig()
    {   $distribute=  new SystemConfigHelper(SystemConfigEnum::DISTRIBUTE_CONFIG) ;
        $model= $distribute->getConfigValue();

        return $model;
    }

}
/**********************End Of DistributeConfig 服务层************************************/


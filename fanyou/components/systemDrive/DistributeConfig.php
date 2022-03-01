<?php

namespace fanyou\components\systemDrive;

use fanyou\enums\SystemConfigEnum;

/**
 * ss
 *
 * @author leedong
 *
 */
class DistributeConfig extends SystemConfigInterface
{
    public function __construct($type= SystemConfigEnum::DISTRIBUTE_CONFIG) {
        parent::__construct($type);
    }
    /**
     * 获取分销
     */
    public function getValue()
    {
        if (!empty($this->configId)) {

            $result = $this->configService->findEffectConfigValue($this->configId);
            if (!empty($result)) {
                return ArrayColumn::getSystemConfigValue($result);
            }
            return null;
        }
    }

}


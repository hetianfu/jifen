<?php

namespace fanyou\components\systemDrive;

use fanyou\enums\SystemConfigEnum;

/**
 * ss
 *
 * @author leedong
 *
 */
class FreightConfig extends SystemConfigInterface
{
    public function __construct($type= SystemConfigEnum::FREIGHT_CONFIG) {
        parent::__construct($type);
    }
    /**
     * 获取积分
     */
    public function getValue(){
        if (!empty($this->configId)) {
            $result = $this->configService->findEffectConfigValue($this->configId);
            if (!empty($result)) {
                $result = ArrayColumn::getSystemConfigValue($result);
                return $result;
            }
            return null;
        }
    }

}


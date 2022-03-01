<?php

namespace fanyou\components\systemDrive;

use fanyou\enums\SystemConfigEnum;

/**
 * ss
 *
 * @author leedong
 *
 */
class MerchantConfig extends SystemConfigInterface
{

    public function __construct($type= SystemConfigEnum::MERCHANT) {
        parent::__construct($type);
    }

    /**
     * 获取积分
     */
    public function getValue()//:MerchantEntity
    {

        if (!empty($this->configId)) {
            $result = $this->configService->findEffectConfigValue($this->configId);
            if (!empty($result)) {
                $result = ArrayColumn::getSystemConfigValue($result);
             //   $config = new MerchantEntity( );
             //   $config->setAttributes($result, false);
                return $result;
            }
            return null;
        }
    }



}


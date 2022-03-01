<?php

namespace fanyou\components\systemDrive;

use fanyou\common\ScoreEntity;
use fanyou\enums\AppEnum;
use fanyou\enums\SystemConfigEnum;

/**
 * ss
 *
 * @author leedong
 *
 */
class ScoreConfig extends SystemConfigInterface
{
    public function __construct($type= SystemConfigEnum::SCORE_CONFIG) {
        parent::__construct($type);
    }
    /**
     * 获取积分
     */
    public function getValue()//:ScoreEntity
    {
        if (!empty($this->configId)) {
            $result = $this->configService->findEffectConfigValue($this->configId);
            if (!empty($result)) {
                $result = ArrayColumn::getSystemConfigValue($result);
               // $config = new ScoreEntity();
               // $config->setAttributes($result, false);
                return $result;
            }
            return null;
        }
    }

}


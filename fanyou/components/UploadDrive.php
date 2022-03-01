<?php

namespace fanyou\components;

use fanyou\components\uploaddrive\Local;
use fanyou\components\uploaddrive\OSS;
use fanyou\enums\StatusEnum;
use fanyou\enums\SystemConfigEnum;
use fanyou\tools\ArrayHelper;

/**
 * Class UploadDrive
 * @package fanyou\components
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-06-29 20:33
 */
class UploadDrive
{
    protected $config = [];
    protected $cert = false;
    /**
     * UploadDrive constructor.
     * @param bool $cert
     */
    public function __construct($cert=false)
    {
        $service = new SystemConfig();
        $this->config =  $service->getConfigInfo(true, SystemConfigEnum::ALI_OSS,StatusEnum::APP);
        $this->cert=$cert;

    }
    /**
     * 本地存储
     * @param array $config
     * @return Local
     */
    public function local( $config = [])
    {
        return new Local(ArrayHelper::merge($this->config, $config));
    }

    /**
     * 阿里云存储
     * @param array $config
     * @return OSS
     */
    public function oss( $config = [])
    {
        return new OSS(ArrayHelper::merge($this->config, $config));
    }

    /**
     * 腾讯云
     * @param array $config
     * @return Cos
     */
    public function cos($config = [])
    {
        return new Cos(ArrayHelper::merge($this->config, $config));
    }

    /**
     * @param array $config
     * @return Qiniu
     */
    public function qiniu($config = [])
    {
        return new Qiniu(ArrayHelper::merge($this->config, $config));
    }
}
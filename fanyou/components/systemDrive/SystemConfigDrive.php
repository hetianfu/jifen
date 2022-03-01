<?php

namespace fanyou\components\systemDrive;


/**
 * Class SystemConfigDrive
 * @package fanyou\components\systemDrive
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-07-10 10:41
 */
class SystemConfigDrive
{

    /**
     * @var array
     */
    protected $config = [];

    /**
     * SystemConfigDrive constructor.
     * @param $type
     */
    public function __construct()
    {
    }

    /**
     * 积分
     * @return ScoreConfig
     */
    public function score()
    {
        return new ScoreConfig();
    }

    public function merchant()
    {
        return new MerchantConfig();
    }

    /**
     * 快递
     * @return FreightConfig
     */
    public function freight()
    {
        return new FreightConfig();
    }

    /**
     * 分销
     */
    public function distribute()
    {
        return new DistributeConfig();
    }


}
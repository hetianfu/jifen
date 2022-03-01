<?php


namespace fanyou\tools;

use fanyou\components\systemDrive\SystemConfigDrive;

/**
 * 分组数据辅助类
 * Class SystemConfigHelper
 * @package fanyou\tools
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-06-01 : 17:49
 */
class SystemConfigHelper
{
    /**
     * @var GroupInterface
     */
    protected $groupDrive;

    /**
     * SystemConfigHelper constructor.
     * @param $type
     */
    public function __construct( $type)
    {
        $service=new SystemConfigDrive();
        $this->groupDrive = $service->$type();
    }
    /**
     * 取值
     */
    public function getConfigValue()
    {
       return $this->groupDrive->getValue();
    }

}
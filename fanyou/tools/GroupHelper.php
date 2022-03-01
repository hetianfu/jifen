<?php


namespace fanyou\tools;

use fanyou\components\groupDrive\GroupDrive;

/**
 * 分组数据辅助类
 * Class GroupHelper
 * @package fanyou\tools
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-07-02 16:11
 */
class GroupHelper
{

    /**
     * @var GroupInterface
     */
    protected $groupDrive;
    protected $limit;
    protected $page;

    /**
     * GroupHelper constructor.
     * @param $type
     * @param string $id
     * @param $fields
     * @param int $page
     * @param int $limit
     */
    public function __construct( $type, $id = '',$fields,$page=1,$limit=10)
    {
        $this->page=$page;
        $this->limit=$limit;

        $service=new GroupDrive($id ,$fields);
        $this->groupDrive = $service->$type();

    }

    /**
     * @param bool $allShow  是否全部展示
     * @return mixed
     */
    public function getGroupValue($allShow=false)
    {
       return $this->groupDrive->getValue($allShow,$this->page,$this->limit);
    }
    public function getGroupTotalCount()
    {

        return $this->groupDrive->getTotalCount();
    }
}
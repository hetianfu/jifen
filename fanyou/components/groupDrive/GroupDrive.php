<?php

namespace fanyou\components\groupDrive;


/**
 * Class GroupDrive
 * @package fanyou\components\groupDrive
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-06-10 17:30
 */
class GroupDrive
{

    /**
     * @var array
     */
    protected $id = [];
    protected $fields ;

    /**
     * GroupDrive constructor.
     * @param $id
     * @param $fields
     */
    public function __construct($id,$fields)
    {
        $this->id =$id;
        $this->fields = $fields;
    }

    /**
     * @return SecKillGroup
     */
    public function STRATEGY()
    {

        return new SecKillGroup($this->id,$this->fields);
    }
    public function PINK()
    {
        return new PinkGroup($this->id,$this->fields);
    }

    public function PRODUCT()
    {
        return new ProductGroup($this->id,$this->fields);
    }
}
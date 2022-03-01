<?php
namespace fanyou\components\groupDrive;

/**
 * Class GroupInterface
 * @package fanyou\components\groupDrive
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-06-10 17:33
 */
abstract class   GroupInterface
{
    public $id;
    public $totalCount;
    public $fields;
    public function __construct($id,$fields=[] )
    {
        $this->id=$id;
        $this->fields=$fields;
        $this->create();
    }

    abstract protected function create();
    abstract protected function getValue();
    abstract protected function getTotalCount();
}


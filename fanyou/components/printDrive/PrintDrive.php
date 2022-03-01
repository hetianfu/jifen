<?php

namespace fanyou\components\printDrive;


use fanyou\enums\entity\PrintBrandEnum;

/**
 * Class PrintDrive
 * @package fanyou\components\groupDrive
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-06-11 9:27
 */
class PrintDrive
{

    /**
     * @var array
     */
    protected $id = [];

    /**
     * GroupDrive constructor.
     * @param $id
     */
    public function __construct($id=[])
    {
        $this->id =$id;
    }

    /**
     * @return FeieyunGroup
     */
    public function feieyun()
    {
        return new FeieyunGroup(PrintBrandEnum::FEI_E_YUN);
    }
    public function ylyun()
    {
        return new YlyunGroup($BRAND=PrintBrandEnum::Y_L_YUN);
    }
}
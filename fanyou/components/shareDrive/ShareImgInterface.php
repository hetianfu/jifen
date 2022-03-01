<?php
namespace fanyou\components\printDrive;

use api\modules\mobile\service\UserShareService;

/**
 * Class PrintInterface
 * @package fanyou\components\printDrive
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-06-10 17:33
 */
abstract class   ShareImgInterface
{

    public $shareService;
    public function __construct()
    {
        $this->shareService=new UserShareService();
        }

    abstract protected function create();

}


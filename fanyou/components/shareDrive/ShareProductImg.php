<?php
namespace fanyou\components\shareDrive;

use fanyou\components\printDrive\ShareImgInterface;
use fanyou\enums\entity\ShareTypeEnum;


/**
 * 生成商品分享图
 * Class ShareProductImg
 * @package fanyou\components\shareDrive
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-07-04 10:31
 */
class ShareProductImg extends ShareImgInterface
{
    private $md5Id ;
    private $userId ;
    private $keyId ;
    private $shareType=ShareTypeEnum::PRODUCT;
    public function __construct($userId,$keyId)
    {
        parent::__construct();
        $this->userId=$userId;
        $this->keyId=$keyId;
        $this->md5Id=md5($userId.$keyId.$this->shareType);
    }
    protected function create(){


    }



}


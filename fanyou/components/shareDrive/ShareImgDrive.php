<?php

namespace fanyou\components\printDrive;


use fanyou\components\shareDrive\ShareProductImg;

/**
 * Class PrintDrive
 * @package fanyou\components\groupDrive
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-06-11 9:27
 */
class ShareImgDrive
{

    /**
     * @var array
     */
    protected $userId ;
    protected $keyId ;

    /**
     * ShareImgDrive constructor.
     * @param $userId
     * @param null $keyId
     */
    public function __construct($userId,$keyId=null)
    {
        $this->userId =$userId;
        if(is_null($keyId)){
            $this->keyId =$userId;
        } else{
            $this->keyId =$keyId;
        }
    }


    /**
     * @return ShareUserImg
     */
    public function  USER_INFO()
    {
        return new ShareUserImg($this->userId);
    }
    public function GOODS_INFO()
    {
        return new ShareProductImg($this->userId,$this->keyId);
    }
}
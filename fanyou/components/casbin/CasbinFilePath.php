<?php
namespace fanyou\components\casbin;

/**
 * Class CasbinFilePath
 * @package fanyou\components\casbin
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-07-14 16:15
 */
class CasbinFilePath
{
    public static  function getConfigFilePath(){
        return __DIR__ .'/casbin-model.conf';
    }

    public static function getFileCsv($fileName){
        return __DIR__ . '/role_'.$fileName.'.csv';
    }

}


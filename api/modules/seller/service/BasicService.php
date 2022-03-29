<?php

namespace api\modules\seller\service;

use Yii;

/**
 * Class Service
 * @package common\components
 * @author Administrator <admin@163.com>
 */
class BasicService
{

    public function deleteCache($cacheKey)
    {
        return   Yii::$app->cache->delete($cacheKey);
    }

    public function getRandomId()
    {
        $randomId=Yii::$app->getSecurity()->generateRandomString();
        if(strpos($randomId, 'in') === 0){
            $randomId=str_replace('in','R_',$randomId);
        }
        return  $randomId;
    }


    public function DbCommand()
    {
        return  Yii::$app->db->createCommand();
    }


    public function array_filter_ext($array )
    {
      return  array_filter($array,function ($item) {
            if ($item === '' || $item === null) {  return false; }  return true;
      });
    }
}
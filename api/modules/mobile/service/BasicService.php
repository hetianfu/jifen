<?php

namespace api\modules\mobile\service;

use Yii;

/**
 * Class BasicService
 * @package api\modules\mobile\service
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-07-08 15:48
 */
class BasicService
{
    public function getRandomId()
    {
        $randomId=Yii::$app->getSecurity()->generateRandomString();
        if(strpos($randomId, 'in') === 0){
            $randomId=str_replace('in','R_',$randomId);
        }
        return  $randomId;
    }

    public function array_filter_ext($array )
    {
      return  array_filter($array,function ($item) {
            if ($item === '' || $item === null) {  return false; }  return true;
      });
    }
}
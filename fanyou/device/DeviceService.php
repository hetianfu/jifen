<?php

namespace fanyou\device;

use fanyou\device\hbh\ComboParams;
use fanyou\device\models\forms\DeviceCommonModel;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

/**
 * Class DeviceCommand
 * @package fanyou\service
 * @date: 2020-09-02 16:02
 */
class DeviceService
{
    public static function sendCommand(DeviceCommonModel $model,$extends)
    {
        if(empty($extends)){
            // 记录错误日志

        }
        $method=$model->method;
        $client = new Client();
      return  $client->$method($model->url, [
          RequestOptions::JSON => ComboParams::getParams($model->type,json_decode($model->params),$extends)
        ]);

    }

}
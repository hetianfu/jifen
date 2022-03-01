<?php

namespace fanyou\device\hbh;


use fanyou\device\enums\CommandTypeEnum;
use fanyou\tools\ArrayHelper;

/**
 * Class ComboParams
 * @package fanyou\device\hbh
 * @date: 2020-09-02 16:18
 */
class ComboParams
{
    public static function getParams($type, $array, $extends)
    {
        $result = [];
        $result = array_merge($result, ArrayHelper::toArray($array));
        switch ($type) {
            case CommandTypeEnum::ADD:
                $result['modelid'] = $extends['deviceId'];
                $result['order_sn'] = $extends['id'];
                $result['address']['name'] = "username";
                $result['address']['mobile'] = "mobile";
                $result['address']['province'] = "province";
                $result['address']['city'] = "city";
                $result['address']['area'] = "area";
                $result['address']['street'] = "street";
                $result['address']['address'] = "address";

                break;
            case CommandTypeEnum::CHARGE:

                $result['appid'] = \Yii::$app->wechat->miniProgram->getConfig()['app_id'];
                $result['wxopenid'] = $extends['openId'];
                $result['deviceid'] = $extends['deviceId'];
                $result['order_sn'] = $extends['id'];
                break;
            case CommandTypeEnum::QUERY_ONLINE:
                $result['deviceid'] = $extends['deviceId'];
                break;
            case CommandTypeEnum::QUERY_LIST:
                $result['openid'] = $extends['openId'];
                $result['appid'] = \Yii::$app->wechat->miniProgram->getConfig()['app_id'];
                break;
        }
        return $result ;

    }

}
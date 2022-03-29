<?php

namespace api\modules\mobile\service\event;

use api\modules\mobile\models\event\OrderPayEvent;
use api\modules\mobile\service\BasicService;
use fanyou\service\ThirdSmsService;
use fanyou\tools\ArrayHelper;
use GuzzleHttp\Exception\GuzzleException;
use EasyWeChat\Kernel\Exceptions\Exception;
use Yii;


/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-03-23
 */
class MessageEventService  extends BasicService
{
    public function __construct()
    {
    }

    /*********************Product模块服务层************************************/
    /**
     * 发送成功消息
     * @param OrderPayEvent $event
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static  function sendPaySuccessMessage(OrderPayEvent  $event)
    {   $order = $event->orderInfo;
       // if(!empty($_ENV['THIRD_SMS_STATUS'])){
            $connect=ArrayHelper::toArray( json_decode($order['connect_snap'] ))  ;
            ThirdSmsService::paySms($connect['name'],$connect['telephone']);
       // }
        try {
            $data = [
                'template_id' => 'rREobFRXnscIke4x4e_NPHOlHobB8xy3FrY_OXnS06Q', // 所需下发的订阅模板id
                'touser' => $event->openId,// 'oSyZp5OBNPBRhG-7BVgWxbiNZm',     // 接收者（用户）的 openid
                'page' => '',       // 点击模板卡片后的跳转页面，仅限本小程序内的页面。支持带参数,（示例index?foo=bar）。该字段不填则模板无跳转。
                'data' => [         // 模板内容，格式形如 { "key1": { "value": any }, "key2": { "value": any } }
                    'thing1' => [
                        'value' => '迅蜂商城',
                    ],
                    'thing2' => [
                        'value' => 'userNam',
                    ],
                    'character_string6' => [
                        'value' => $order->id,
                    ],
                ],
            ];
          Yii::$app->wechat->miniProgram->subscribe_message->send($data);
        } catch (GuzzleException $ge ){
        } catch ( Exception $e ){
        }
    }

}
/**********************End Of Product 服务层************************************/


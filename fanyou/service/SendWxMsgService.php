<?php

namespace fanyou\service;

use api\modules\seller\models\forms\WechatMessageModel;
use EasyWeChat\Kernel\Exceptions\Exception;
use fanyou\enums\NumberEnum;
use fanyou\enums\StatusEnum;
use GuzzleHttp\Exception\GuzzleException;
use Yii;

/**
 * Class FanYouSystemGroupService
 * @package fanyou\service
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-06-10 10:34
 */
class SendWxMsgService
{
    /**
     * 发货通知
     * @param $orderId
     * @param $openId
     * @param string $expressName
     * @param string $express_no
     */
    public static function orderSendMessage($orderId, $openId, $expressName = '迅蜂快递', $express_no = '88-88')
    {
        $templateId = WechatMessageModel::findOne(NumberEnum::ONE)->template_id;
        try {
            $data = [
                'template_id' => $templateId,
                'touser' => $openId,
                'page' => 'pages/order/info/index?id=' . $orderId,
                'data' => [

                    'thing3' => [
                        'value' => $expressName,
                    ],
                    'time6' => [
                        'value' => date("Y-m-d H:i:s"),
                    ],
                    'character_string4' => [
                        'value' => $express_no,//$order->express_no
                    ],

                    'character_string5' => [
                        'value' => $orderId,
                    ],
                ],
            ];
            Yii::$app->wechat->miniProgram->subscribe_message->send($data);
        } catch (GuzzleException $ge) {
            print_r($ge);
            exit;
        } catch (Exception $e) {
            print_r($e);
            exit;
        }

    }

    /**
     * 核销通知
     * @param $orderId
     * @param $openId
     * @param string $sellerName
     * @param int $code
     */
    public static function orderCheckMessage($orderId, $openId, $sellerName = '', $code = 00)
    {
        $templateId = WechatMessageModel::findOne(NumberEnum::TWO)->template_id;
        try {
            $data = [
                'template_id' => $templateId,
                'touser' => $openId,
                'page' => 'pages/order/info/index?id=' . $orderId,
                'data' => [
                    'character_string7' => [
                        'value' => $orderId,
                    ],
                    'thing1' => [
                        'value' => $sellerName,
                    ],
                    'number2' => [
                        'value' => $code,
                    ],
                    'date4' => [
                        'value' => date("Y-m-d H:i:s"),
                    ],
                ],
            ];
            Yii::$app->wechat->miniProgram->subscribe_message->send($data);
        } catch (GuzzleException $ge) {
        } catch (Exception $e) {
        }
    }

    /**
     * 提现通知
     * @param $amount
     * @param $openId
     * @param int $result
     */
    public static function drawCashMessage($amount, $openId, $result = StatusEnum::SUCCESS)
    {
        $templateId = WechatMessageModel::findOne(NumberEnum::FOUR)->template_id;
        try {
            $data = [
                'template_id' => $templateId,
                'touser' => $openId,
                'page' => '/pages/cash/home/index',
                'data' => [
                    'amount3' => [
                        'value' => NumberEnum::N_ONE * $amount . '元',
                    ],
                    'phrase1' => [
                        'value' => $result ? '提现成功' : '未通过',
                    ],
                    'date6' => [
                        'value' => date("Y-m-d H:i:s"),
                    ],
                ],
            ];
            Yii::$app->wechat->miniProgram->subscribe_message->send($data);

        } catch (GuzzleException $ge) {
        } catch (Exception $e) {
        }
    }

    /**
     * 拼团结果通知
     * @param $openId 接收者
     * @param $productName 活动名称
     * @param $pinkNumber 成团人数
     * @param $leader 团长
     * @param int $result
     */
    public static function pinkResultMessage($openId, $productName, $pinkNumber, $leader, $result = StatusEnum::SUCCESS)
    {

        $templateId = WechatMessageModel::findOne(['id' => NumberEnum::FIVE, 'status' => StatusEnum::ENABLED])->template_id;

        try {
            $data = [
                'template_id' => $templateId,
                'touser' => $openId,
                'page' => '/pages/order/list/index',
                'data' => [
                    'thing1' => [
                        'value' => $productName,
                    ],
                    'number2' => [
                        'value' => $pinkNumber,
                    ],
                    'name8' => [
                        'value' => $leader,
                    ],
                    'phrase7' => [
                        'value' => $result ? '组团成功' : '组团失败',
                    ],
                ],
            ];
            Yii::$app->wechat->miniProgram->subscribe_message->send($data);

        } catch (GuzzleException $ge) {
        } catch (Exception $e) {
        }
    }

}
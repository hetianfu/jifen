<?php

namespace fanyou\service;

use api\modules\seller\models\forms\SmsLogModel;
use fanyou\components\sms\SmsService;
use fanyou\enums\HttpErrorEnum;
use fanyou\enums\SmsEnum;
use fanyou\error\FanYouHttpException;
use fanyou\tools\ArrayHelper;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use yii\debug\panels\RequestPanel;

/**
 *第三方短信服务
 * @property false|string authContent
 * @package fanyou\service
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-06-10 10:34
 */
class ThirdSmsService
{
    //const ORDER_URL = "www.0516hz.com";
    const SMS_CUI = '【淘拼优选】亲爱的#收件人姓名#，您有一笔订单尚未支付，商品库存不多，点#订单短链接#付款后优先安排发货l更多优患请关注公众号:淘拼优选家回T退';
    const SMS_PAY = '【淘拼优选】尊敬的#收件人姓名#，您已成功下单。关注微信公众号淘拼优选可以查询售后跟踪订单哦~感谢您的支持，祝您生活愉快!';
    const SMS_SEND = '【淘拼优选】尊敬的#收件人姓名#，您购买的宝贝已打包发货，快递单号:#快递单号#点#订单短链接#可跟踪快递信息。关注微信公众号淘拼优选家有更多优思峨';
    const SMS_REFUND = '【淘拼优选】您的订单已退款成功，将在24小时内退回原支付渠道，请您留意到账情况，更多咨询请关注公众号：淘拼优选家';

    /**
     * 获取当前帐户余额
     * @return array|mixed|object|string
     * @throws FanYouHttpException
     */
    static function getInfo()
    {
        $tm = date("YmdHis", time());
        $pw = md5($_ENV['THIRD_SMS_PW'] . $tm);
        $url = "http://101.132.37.195:18002/balance.do?uid=" . $_ENV['THIRD_SMS_UID'] . "&pw=" . $pw . '&tm=' . $tm;

        $client = new Client();
        try {
            $response = $client->get($url, [
                RequestOptions::HEADERS => ['Content-Type' => 'application/x-www-form-urlencoded']
            ]);
            $body = $response->getBody()->getContents(); //获取响应体，对象
            $e = ArrayHelper::toArray(json_decode($body));
            if ($e['code'] != 200) {
                throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, '线路繁忙！');
            }
        } catch (\Exception $e) {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, '系统繁忙！');
        }
        return $e['data'];
    }
    static function complainSms( $telephone)
    {
        if (empty($telephone)) {
            return;
        }
        $smContent ="【联合优选家】您的投诉已经收到，客服将在24小时内给您回电!";
        self::batchSendSms($smContent, $telephone,SmsEnum::COMPLAIN);
    }
    /**
     * 催单提醒
     * @param $name
     * @param $telephone
     * @throws FanYouHttpException
     */
    static function cuiSms($name, $telephone)
    {
        if (empty($name) || empty($telephone)) {
            return;
        }
        $smContent = str_replace("#收件人姓名#", $name, self::SMS_CUI);
        $smContent = str_replace("#订单短链接#", $_ENV['SMS_SHORT_URL'], $smContent);

        self::batchSendSms($smContent, $telephone,SmsEnum::REMINDER);
    }

    /**
     * 下单提醒
     * @param $name
     * @param $telephone
     * @throws FanYouHttpException
     */
    static function paySms($name, $telephone)
    {
        if (empty($name) || empty($telephone)) {

            return;
        }


        $smContent = str_replace("#收件人姓名#", $name, self::SMS_PAY);
        SmsService::sendSms($telephone, SmsEnum::BUY,$smContent);
        self::batchSendSms($smContent, $telephone,SmsEnum::BUY);
    }


    static function sendSms($name, $telephone, $orderId)
    {
        if (empty($name) || empty($telephone)) {

            return;
        }
        $smContent = str_replace("#收件人姓名#", $name, self::SMS_SEND);
        $smContent = str_replace("#快递单号#", $orderId, $smContent);
         $smContent = str_replace("#订单短链接#", $_ENV['SMS_SHORT_URL'], $smContent);
        SmsService::sendSms($telephone, SmsEnum::SEND, $smContent);
        self::batchSendSms($smContent, $telephone, SmsEnum::SEND);
    }

    /**
     * 退单提醒
     * @param $smContent
     * @param $telephone
     * @throws FanYouHttpException
     */
    static function refundSms($telephone)
    {
        if (empty($telephone)) {
            return;
        }
        SmsService::sendSms($telephone, SmsEnum::REFUND, []);
        self::batchSendSms(self::SMS_REFUND, $telephone,SmsEnum::REFUND);
    }

    /**
     * 发送短信
     * @param $expressNo
     * @param string $shopId
     * @return array|mixed|object|string
     * @throws FanYouHttpException
     */
    static function batchSendSms($smContent, $telephone,$type=SmsEnum::SEND,$id='')
    {

        try {
          $url = 'http://smssh1.253.com/msg/send/json';
          //创蓝接口参数
          $postArr =array(
            'account' =>"N4402737",
            'password' => "md69a3wLk",
            'msg' => urlencode($smContent),
            'phone' => $telephone,
          );
          $result = self::curlPost($url, $postArr);
            $e = json_decode($result,true);
            $smsLog = new  SmsLogModel();
            $smsLog->sms_code=$e['code'];
            $smsLog->sms_type=$type;
            $smsLog->telephone = $telephone;
            $smsLog->content = $smContent;
            $smsLog->status = 1;
            if (empty($e) ) {
                $smsLog->status = 0;
            }
            if(empty($id)){
                $smsLog->insert();
            }else{
                $smsLog->id=$id;
                $smsLog->setOldAttribute('id',$id);
                $smsLog->update();
            }

        } catch (\Exception $error) {

        }
        return $e;
    }


  /** *通过CURL发送HTTP请求 *@paramstring
   * $url //请求URL *@paramarray
   * $postFields //请求参数 *@returnmixed * */
  public static function curlPost($url, $postFields)
  {
    $postFields = json_encode($postFields);
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL, $url);
    curl_setopt($ch,CURLOPT_HTTPHEADER,
      array( 'Content-Type: application/json; charset=utf-8' ) );
    curl_setopt($ch,CURLOPT_IPRESOLVE,CURL_IPRESOLVE_V4);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch,CURLOPT_POST, 1);
    curl_setopt($ch,CURLOPT_POSTFIELDS, $postFields);
    curl_setopt($ch,CURLOPT_TIMEOUT, 60);
    curl_setopt($ch,CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, 0);
    $ret = curl_exec($ch);
    if(false== $ret)
    {
      $result = curl_error($ch);
    }else{
      $rsp = curl_getinfo($ch,CURLINFO_HTTP_CODE);
      if(200 != $rsp) {
        $result = "请求状态" . $rsp . "" . curl_error($ch);
      }else{
        $result = $ret;
      }
    }
    curl_close($ch);
    return$result;
  }

}

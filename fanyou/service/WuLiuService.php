<?php

namespace fanyou\service;

use fanyou\enums\HttpErrorEnum;
use fanyou\error\FanYouHttpException;
use fanyou\tools\ArrayHelper;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

/**
 * Class SystemLogService
 * @property false|string authContent
 * @package fanyou\service
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-06-10 10:34
 */
class WuLiuService
{
  /**
   * 获取当前帐户余额
   * @return array|mixed|object|string
   * @throws FanYouHttpException
   */
  static function getInfo()
  {
    $authContent = file_get_contents(dirname(dirname(__DIR__)) . $_ENV['PEM_PATH']);

    $url = $_ENV['THIRD_IP'] . "/platform/cloud/merchant-gather/get";

    $client = new Client();
    $response = $client->get($url, [
      RequestOptions::HEADERS => ['Authorization' => 'Bearer ' . $authContent]
    ]);
    $body = $response->getBody()->getContents(); //获取响应体，对象
    $e = ArrayHelper::toArray(json_decode($body));
    if ($e['code'] != 200) {
      throw new FanYouHttpException('系统繁忙');
    }
    return $e['data'];
  }

  static function getGatherDetail($page, $size, $shopId = '0')
  {
    $authContent = file_get_contents(dirname(dirname(__DIR__)) . $_ENV['PEM_PATH']);
    $url = $_ENV['THIRD_IP'] . "/platform/cloud/merchant-gather/detail/page/" . $page . "/size/" . $size;
    $url .=   '?domain=' . FanYouSystemGroupService::getDm();
    if (!empty($shopId)) {
      $url = $url . '&merchantId=' . $shopId;
    }

    $client = new Client();
    try {
      $response = $client->get($url, [
        RequestOptions::HEADERS => ['Authorization' => 'Bearer ' . $authContent]
      ]);
      $body = $response->getBody()->getContents();
      $e = ArrayHelper::toArray(json_decode($body));
    } catch (\Exception $e) {
      $r['list'] = [];
      $r['total'] = 0;
      return $r;
    }
    if ($e['code'] != 200) {
      throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, $e['message']);
    }

    return $e['data'];
  }

  /**
   * 查询物流
   * @param $expressNo
   * @param string $shopId
   * @return array|mixed|object|string
   * @throws FanYouHttpException
   */
  static function queryKdi($expressNo, $shopId = '0')
  {

    if (empty($shopId)) {
      $shopId = '0';
    }
    $authContent = '9a0b85e7b4ff4290b5779d72fa3af872';
    $url =  "https://wdexpress.market.alicloudapi.com/gxali";
    $url = $url . '?n=' . $expressNo;
    $client = new Client();
    try {
      $response = $client->get($url, [
        RequestOptions::HEADERS => ['Authorization' => 'APPCODE ' . $authContent]

      ]);
      $body = $response->getBody()->getContents(); //获取响应体，对象
      $e = ArrayHelper::toArray(json_decode($body));


    } catch (\Exception $error) {
      throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,'系统繁忙！');
    }
    if (!$e['Success']) {
      throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, $e['message']);
    }

    return $e['Traces'];
  }


}

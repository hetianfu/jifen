<?php

namespace fanyou\service;

use fanyou\enums\HttpErrorEnum;
use fanyou\error\FanYouHttpException;
use fanyou\tools\ArrayHelper;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

/**
 * Class GatherProductService
 * @package fanyou\service
 */
class GatherProductService
{

    /**
     * 采集商品
     * @param $productUrl
     * @param string $shopId
     * @return array|mixed|object|string
     * @throws FanYouHttpException
     */
    static function gatherProduct($productUrl, $shopId = '0')
    {
        $authContent = file_get_contents(dirname(dirname(__DIR__)) . $_ENV['PEM_PATH']);
        if (empty($shopId)) {
            $shopId = '0';
        }
        $url = $_ENV['THIRD_IP'] . "/platform/gather/product/merchant/" . $shopId ;
        $requestBody=['url'=>$productUrl,"domain"=>FanYouSystemGroupService::getDm()];
        $client = new Client();
        try {
            $response = $client->post($url, [
                RequestOptions::HEADERS => ['Authorization' => 'Bearer ' . $authContent],
                RequestOptions::JSON=> $requestBody
            ]);
            $body = $response->getBody()->getContents(); //获取响应体，对象

            $e = ArrayHelper::toArray(json_decode($body));
        } catch (\Exception $error) {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,'系统繁忙！');
        }
        if ($e['code'] != 200) {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, $e['message']);
        }
        $e['data']['sale_strategy']='NORMAL';
        $e['data']['pay_type']=['wx','alipay'];
        return $e['data'];
    }


    /**
     * 采集商品
     * @param $productUrl
     * @param string $shopId
     * @return array|mixed|object|string
     * @throws FanYouHttpException
     */
    static function oldGatherProduct($productUrl, $shopId = '0')
    {
        $authContent = file_get_contents(dirname(dirname(__DIR__)) . $_ENV['PEM_PATH']);
        if (empty($shopId)) {
            $shopId = '0';
        }
        $url = $_ENV['THIRD_IP'] . "/platform/gather/product/merchant/" . $shopId ;
        $url = $url . '?url=' . $productUrl;
        $url .=   '&domain=' . FanYouSystemGroupService::getDm();
        $client = new Client();
        try {
            $response = $client->get($url, [
                RequestOptions::HEADERS => ['Authorization' => 'Bearer ' . $authContent]
            ]);
            $body = $response->getBody()->getContents(); //获取响应体，对象
            $e = ArrayHelper::toArray(json_decode($body));
        } catch (\Exception $error) {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,'系统繁忙！');
        }
        if ($e['code'] != 200) {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, $e['message']);
        }

        return $e['data'];
    }


}
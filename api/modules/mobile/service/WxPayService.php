<?php

namespace api\modules\mobile\service;

use fanyou\enums\HttpErrorEnum;
use fanyou\error\FanYouHttpException;
use Yii;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-04-23
 */
class WxPayService extends BasicService
{

/********************wxPay模块服务层************************************/

    /**
     * 设置子商户
     * @param $subMchId
     * @param string $subAppId
     * @return \EasyWeChat\Payment\Application
     */
    public  function  setSubMerchant($subMchId,$subAppId=""){
      return  Yii::$app->wechat->payment->setSubMerchant($subMchId ,$subAppId);
    }

    /**
     * 拉起支付
     * @param $prepayId
     * @return array|string
     */
    public  function  getJsSdk($prepayId){

        return  Yii::$app->wechat->payment->jssdk->bridgeConfig($prepayId, false);
    }
 

    /**
     * 统一下单，获取prePayId
     * @param $array
     * @return mixed
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public  function  getPrePayId($array){
         return $this->unifyPayOrder( $array)['prepay_id'];
    }

    /**
     * H5获取支付跳转连接
     * @param $array
     * @return mixed|string
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public  function  getWebUrlId($array){

        return $this->unifyPayOrder( $array)['mweb_url'];
    }

    /**
     * 子商户$subAppId 为可选项
     * @return array
     */
    public  function  getPayConfig(){
        return  Yii::$app->wechat->payment->getConfig();
    }

    /**
     * 获取支付密钥
     * @return string
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     */
    public  function  getWxPayKey( ){
        //
        return  Yii::$app->wechat->payment->getKey();
    }

    /**
     * 刷卡支付
     * @param $array
     * @return mixed
     */
    public  function  freshCardPay( $array){
        return  Yii::$app->wechat->payment->pay($array);
    }

    /**
     * 生成产品二维码
     * @param $productId
     * @return string
     */
    public  function  schemeCode($productId){
        // 刷卡支付
        return  Yii::$app->wechat->payment->scheme($productId);
    }

    /**
     * 统一下单接口
     * @param $array
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public  function  unifyPayOrder( $array){
         return  Yii::$app->wechat->payment->order->unify($array);
    }

    /**
     * 根据商户订单号查询订单
     * @param $outTradeNo
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     */
   public  function  getPayOrderInfo( $outTradeNo){
        return  Yii::$app->wechat->payment->order->queryByOutTradeNumber($outTradeNo);
    }

    /**
     * 关闭订单
     * @param $outTradeNo
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public  function  closedPayOrder( $outTradeNo){
        return  Yii::$app->wechat->payment->order->close($outTradeNo);
    }

    /**
     * 企业付款至零钱
     * @param $data
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public  function  mchPayToBalance( $data){
//      $data=  [
//            'partner_trade_no' => '1233455', // 商户订单号，需保持唯一性(只能是字母或者数字，不能包含有符号)
//            'openid' => 'oxTWIuGaIt6gTKsQRLau2M0yL16E',
//            'check_name' => 'FORCE_CHECK', // NO_CHECK：不校验真实姓名, FORCE_CHECK：强校验真实姓名
//            're_user_name' => '王小帅', // 如果 check_name 设置为FORCE_CHECK，则必填用户真实姓名
//            'amount' => 10000, // 企业付款金额，单位为分
//            'desc' => '理赔', // 企业付款操作说明信息。必填
//        ]
        return  Yii::$app->wechat->payment->transfer->toBalance($data);
    }

    /**
     * 生成签名
     * @param $data
     * @return string
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     */
    public function makeSign($data){
        //sign不参与签名算法
        unset($data['sign']);
        //获取微信支付秘钥
        $key =  $this->getWxPayKey();
        //去空
        $data = array_filter($data);
        //签名步骤一：按字典序排序参数
        ksort($data);
        $string_a = http_build_query($data);
        $string_a = urldecode($string_a);
        //签名步骤二：在string后加入KEY
        $string_sign_temp = $string_a."&key=".$key;
        //签名步骤三：MD5加密
        $sign = md5($string_sign_temp);
        $result = strtoupper($sign);
        return $result;
    }
}
/**********************End Of wxPay模块服务层************************************/


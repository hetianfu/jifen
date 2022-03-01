<?php

namespace api\modules\seller\service\wechat;

use api\modules\seller\models\forms\RefundOrderApplyModel;
use fanyou\enums\NumberEnum;
use Yii;

/**
 *  微信退单
 * Create Time: 2020-04-23
 */
class WxPayService
{

/*********************Coupon模块服务层************************************/

    /**
     * 同意退款
     * @param RefundOrderApplyModel $model
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     */
    public  function  refundPayOrder(RefundOrderApplyModel $model){
       $config['refund_desc']= $model->remark;

       $arr=Yii::$app->wechat->payment->refund->byOutTradeNumber($model->pay_order_id,
       $model->refund_id,intval(strval($model->order_amount*NumberEnum::HUNDRED)),intval(strval($model->refund_amount*NumberEnum::HUNDRED)),$config);

       return $arr;

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

    /*生成签名*/
//    public function makeSign($data){
//        //sign不参与签名算法
//        unset($data['sign']);
//        //获取微信支付秘钥
//        $key =  $this->getWxPayKey();//$this->wxPayService->getWxPayKey();
//        //去空
//        $data = array_filter($data);
//        //签名步骤一：按字典序排序参数
//        ksort($data);
//        $string_a = http_build_query($data);
//        $string_a = urldecode($string_a);
//        //签名步骤二：在string后加入KEY
//        $string_sign_temp = $string_a."&key=".$key;
//        //签名步骤三：MD5加密
//        $sign = md5($string_sign_temp);
//        $result = strtoupper($sign);
//        return $result;
//    }
}
/**********************End Of Coupon 服务层************************************/


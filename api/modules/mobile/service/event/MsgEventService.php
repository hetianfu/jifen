<?php

namespace api\modules\mobile\service\event;

use api\modules\mobile\models\event\OrderPayEvent;
use api\modules\mobile\service\BasicService;
use api\modules\seller\models\forms\SmsLogModel;
use api\modules\seller\models\forms\SmsTemplateModel;
use fanyou\components\sms\SmsService;
use fanyou\enums\HttpErrorEnum;
use fanyou\enums\SmsEnum;
use fanyou\error\FanYouHttpException;
use fanyou\tools\ArrayHelper;
use fanyou\tools\StringHelper;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-04-23
 */
class MsgEventService extends BasicService
{
    /*********************支付订单成功后，事件************************************/

    /**
     */
     public static function actionSendRegMsg(OrderPayEvent  $event)
    {
        try{
        $orderInfo = $event->orderInfo;
        $stockList= ArrayHelper::toArray(json_decode($orderInfo['cart_snap']) );
        $stock=$stockList[0]['name'];
        $length=  mb_strlen($stock,'utf-8');
        if($length>15){
         $stock=  mb_substr($stock, 0, 15, 'utf-8')."..";
        }
        $telephone =ArrayHelper::toArray(json_decode($orderInfo['connect_snap']))['telephone']  ;
        $template = SmsTemplateModel::findOne(["type" => SmsEnum::BUY]);
        if(empty($template) || empty($telephone)){
            return ;
        }
        $templateCode = $template->code;
        $signName = $template->sign_name;
        $request['name'] = '['.$stock.']';
        $codeArray =  ArrayHelper::toArray(json_decode( $template->template_map));
        foreach ($codeArray as $item) {
            isset($request[$item]) && $array[$item] = $request[$item];
        }
        SmsService::sendSms($telephone, SmsEnum::BUY, $templateCode);

        $content = SmsService::sendMsg($telephone, $templateCode, $signName, $array);
        if (!empty($content)) {
            $array = ArrayHelper::toArray($content);
            if ($array['Message'] != 'OK') {
              //  print_r($array);exit;
                throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, '短信发送失败！');
            }
            $log = new SmsLogModel();
            $log->telephone = $telephone;
            $log->code = "*";
            $log->sms_type = $template->type;
            $log->sms_code = $templateCode;
            $log->ali_message = $array['Message'];
            $log->ali_request_id = $array['RequestId'];
            $log->ali_biz_id = $array['BizId'];
            $log->insert();
            return 1;
        }
        }catch (Exception $e){

        }
    }

}
/**********************End Of Coupon 服务层************************************/


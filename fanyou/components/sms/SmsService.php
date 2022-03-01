<?php

namespace fanyou\components\sms;

use Aliyun\DySDKLite\SignatureHelper;
use api\modules\seller\models\forms\SmsLogModel;
use api\modules\seller\models\forms\SmsTemplateModel;
use api\modules\seller\models\forms\SystemConfigTabModel;
use api\modules\seller\models\forms\SystemConfigTabValueModel;
use fanyou\components\systemDrive\ArrayColumn;
use fanyou\enums\AppEnum;
use fanyou\enums\HttpErrorEnum;
use fanyou\enums\SmsEnum;
use fanyou\enums\StatusEnum;
use fanyou\enums\SystemConfigEnum;
use fanyou\error\FanYouHttpException;
use yii\base\BaseObject;

/**
 * Class FanYouSystemGroupService
 * @package fanyou\service
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-06-10 10:34
 */
class SmsService
{
    public static function sendMsg($telephone, $templateCode = "SMS_182830756", $signName = AppEnum::GLOBAL_NAME, $templateParam)
    {
        $configTab = SystemConfigTabModel::find()->where(['eng_title' => SystemConfigEnum::SYSTEM_SMS])->asArray()->one();
        if ($configTab) {
            $systemSmsConfig = SystemConfigTabValueModel::find()
                ->select(['id', 'menu_name', 'value',])
                ->where(['config_tab_id' => $configTab['id']])
                ->asArray()
                ->all();
            $configArray = ArrayColumn::getSystemConfigValue($systemSmsConfig);
            if (empty($configArray['sms_account'])) {
                throw new FanYouHttpException(HttpErrorEnum::Expectation_Failed, '短信参数配置错误');
            }
            // fixme 必填: 请参阅 https://ak-console.aliyun.com/ 取得您的AK信息
            $accessKeyId = $configArray['sms_account'];
            $accessKeySecret = $configArray['sms_token'];
        }
        $params = array();
        // *** 需用户填写部分 ***
        // fixme 必填：是否启用https
        $security = false;

        // fixme 必填: 短信接收号码
        $params["PhoneNumbers"] = $telephone;

        // fixme 必填: 短信签名，应严格按"签名名称"填写，请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/sign
        $params["SignName"] = $signName;

        // fixme 必填: 短信模板Code，应严格按"模板CODE"填写, 请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/template
        $params["TemplateCode"] = $templateCode;

        // fixme 可选: 设置模板参数, 假如模板中存在变量需要替换则为必填项
        $params['TemplateParam'] =$templateParam;

        // fixme 可选: 设置发送短信流水号
        //$params['OutId'] = "12345";

        // fixme 可选: 上行短信扩展码, 扩展码字段控制在7位或以下，无特殊需求用户请忽略此字段
        //$params['SmsUpExtendCode'] = "1234567";

        // *** 需用户填写部分结束, 以下代码若无必要无需更改 ***
        if (!empty($params["TemplateParam"]) && is_array($params["TemplateParam"])) {
            $params["TemplateParam"] = json_encode($params["TemplateParam"], JSON_UNESCAPED_UNICODE);
        }
        // 初始化SignatureHelper实例用于设置参数，签名以及发送请求
        $helper = new SignatureHelper();

        // 此处可能会抛出异常，注意catch
        $content = $helper->request(
            $accessKeyId,
            $accessKeySecret,
            "dysmsapi.aliyuncs.com",
            array_merge($params, array(
                "RegionId" => "cn-hangzhou",
                "Action" => "SendSms",
                "Version" => "2017-05-25",
            )),
            $security
        );
        return $content;
    }


  /**
   * 创蓝发送短信接口调取
   * @param $telephone  手机号
   * @param $templateCode //短信code
   * @param string $signName //签名
   * @param $templateParam//参数
   * @return int
   */
  public static function sendSms($telephone, $type , $msg)
  {

    $url = 'http://smssh1.253.com/msg/send/json';
    //创蓝接口参数
    $postArr =array(
      'account' =>"N4402737",
      'password' => "md69a3wLk",
      'msg' => urlencode($msg),
      'phone' => $telephone,
    );
    $result = self::curlPost($url, $postArr);
    var_dump($result);die;



    $template = SmsTemplateModel::findOne(["type" =>$type]);
    $templateCode = $template->code;
    $signName = $template->sign_name;
    $log = new SmsLogModel();
    $log->telephone = $telephone;
    $log->code = $type;
    $log->sms_type = $template->type;
    $log->sms_code = $templateCode;
    $log->content = urlencode($msg);
    $log->insert();
    return 1;
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

<?php

namespace api\modules\mobile\controllers;

use api\modules\auth\ApiAuth;
use api\modules\mobile\models\forms\UserInfoModel;
use api\modules\mobile\models\UserInfoResult;
use api\modules\mobile\service\CategoryService;
use api\modules\mobile\service\LoginService;
use api\modules\seller\models\forms\ChannelModel;
use api\modules\seller\models\forms\SmsLogModel;
use api\modules\seller\models\forms\SmsTemplateModel;
use fanyou\components\sms\SmsService;
use fanyou\enums\HttpErrorEnum;
use fanyou\enums\NumberEnum;
use fanyou\enums\SmsEnum;
use fanyou\error\FanYouHttpException;
use fanyou\service\UserHostIp;
use fanyou\tools\ArrayHelper;
use fanyou\tools\StringHelper;
use Yii;
use yii\web\HttpException;

/**
 * zl_category
 * @author E-mail: Administrator@qq.com
 * Create Time: 2019/03/29
 */
class SmsController extends BaseController
{

    private $service;
    private $loginService;
    public function init()
    {
        parent::init();
        $this->service = new CategoryService();
        $this->loginService = new LoginService();
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => ApiAuth::class,
            'optional' => ['send-reg-msg','msg-reg','refresh']  //'msg-reg'
        ];
        return $behaviors;
    }
    /*********************Category模块控制层************************************/

    /**
     * 获取全部对象列表
     * @return mixed
     * @throws HttpException
     */
    public function actionSendRegMsg()
    {

        $request = parent::getRequestPost(false, false);
        $telephone = $request['telephone'];
        $template = SmsTemplateModel::findOne(["type" => SmsEnum::REG]);
        $templateCode = $template->code;
        $signName = $template->sign_name;
        $sendCode = StringHelper::randomNum(false, 4);
        $request['code'] = $sendCode;


        $codeArray =  ArrayHelper::toArray(json_decode( $template->template_map));
        foreach ($codeArray as $item) {
            isset($request[$item]) && $array[$item] = $request[$item];
        }

        $content = SmsService::sendSms($telephone, 'REG',$templateCode );


       // SmsService::sendSms($telephone, SmsEnum::REG, $templateCode);


        if (!empty($content)) {
            $array = ArrayHelper::toArray($content);
            if ($array['Message'] != 'OK') {
                //throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, json_encode($array,JSON_UNESCAPED_UNICODE));
                throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, '短信发送失败！');
            }
            $log = new SmsLogModel();
            $log->telephone = $telephone;
            $log->code = $sendCode;
            $log->sms_type = $template->type;
            $log->sms_code = $templateCode;
            $log->ali_message = $array['Message'];
            $log->ali_request_id = $array['RequestId'];
            $log->ali_biz_id = $array['BizId'];
            $log->insert();
            return 1;
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, '短信发送失败-！');
        }
    }
    /**
     * 短信注册
     * @return array
     * @throws FanYouHttpException
     * @throws \Throwable
     */
    public function actionMsgReg()
    {
        $request = parent::getRequestPost(false, false);
        $userId=parent::getUserId();
        $telephone = $request['telephone'];
        $code = $request['code'];
        $openId = $request['openId'];

        //渠道来源
        $sourceId = $request['sourceId'];
      $ChannelModel =   ChannelModel::find()->where(['code'=>$sourceId])->one();
      if ($ChannelModel){
        $sourceId = $ChannelModel->id;
      }else{
        $ChannelModel = UserInfoModel::find()->one();
        $sourceId = $ChannelModel->id;
      }

        $log=SmsLogModel::find()->where(['telephone'=>$telephone,'code'=>$code])->andWhere(['>','created_at',time()-300])->asArray()->one();

        if(empty($log)  || ( $log['ali_message'] !== 'OK' )){
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, '验证码错误，请重新发送！');
        }
        if(empty($openId)){
            $userInfo=UserInfoModel::findOne(['telephone'=>$telephone]);
        }else{
            $userInfo=UserInfoModel::findOne(['open_id'=>$openId]);
            $updateInfo=new UserInfoModel();
            $updateInfo->setOldAttribute('id', $userInfo->id);
            $updateInfo->id=$userInfo->id;
            $updateInfo->telephone=$userInfo->telephone;
            $updateInfo->update();
        }
        if(empty($userInfo)){
            $userInfo=new UserInfoModel();
            $userInfo->id=StringHelper::uuid();
            $userInfo->telephone=$telephone;
            $userInfo->source_id=$sourceId;
            $userInfo->open_id=$openId;
            $userInfo->last_log_in_ip = UserHostIp::getIP();
            $channel=ChannelModel::findOne($sourceId);
            if(!empty($channel)){
                $userInfo->total_score=$channel->score;
                $userInfo->source_json=json_encode(['url'=>$channel->short_url,'name'=>$channel->name],JSON_UNESCAPED_UNICODE);
            }else{
                $channel=ChannelModel::find()->one();
                $userInfo->total_score=$channel->score;
            }
            $userInfo->last_log_in_ip=  $_SERVER["REMOTE_ADDR"];
            $userInfo->last_log_in_at= time();
            $userInfo->insert();
        }else{

        }
        unset($userInfo->source_json);

        $userResult = new UserInfoResult();
        if (!empty($userInfo)) {
            $token = $this->loginService->createAuthKey($userInfo,NumberEnum::TEN_DAYS);

            $userResult->token = $token;
            $userResult->userInfo = $userInfo;
        }
        return ArrayHelper::toArray($userResult);
    }

    /**
     * 刷新ton
     */
    public function actionRefresh()
    {
        $loginHeader = Yii::$app->request->getHeaders();
        $token=$loginHeader['access-token'];
        $userInfo = ArrayHelper::toArray(json_decode($this->loginService->getAuthInfo($token)));
        $userResult = new UserInfoResult();
        if (!empty($userInfo)) {
            $userResult->token = $token;
            $userInfo = UserInfoModel::find()->select(['id', 'total_score', 'amount', 'telephone', 'sex', 'identify', 'source_id'])->where(['id' =>$userInfo['id']])->one();
            $userResult->userInfo = $userInfo;
        }
        return  $userResult ;
    }

}
/**********************End Of Category 控制层************************************/ 
 


<?php

namespace api\modules\mobile\controllers;

use api\modules\auth\ApiAuth;
use api\modules\mobile\models\forms\UserInfoModel;
use api\modules\mobile\models\UserInfoResult;
use api\modules\mobile\service\LoginService;
use api\modules\seller\models\forms\ChannelQuery;
use api\modules\seller\service\ChannelService;
use api\modules\seller\models\forms\ChannelModel;
use fanyou\enums\NumberEnum;
use fanyou\error\FanYouHttpException;
use fanyou\enums\HttpErrorEnum;
use fanyou\service\UserHostIp;
use fanyou\tools\ArrayHelper;
use fanyou\tools\StringHelper;
use Yii;
use yii\web\HttpException;

/**
 * Class ChannelController
 * @package api\modules\seller\controllers
 * @author: HenryJia
 * @E-mail: 316560925@qq.com
 * @date: 2020-11-16
 */
class ChannelController extends BaseController
{
  private $service;
  private $loginService;

  public function init()
  {
    parent::init();
    $this->service = new ChannelService();
    $this->loginService = new LoginService();
  }

  public function behaviors()
  {
    $behaviors = parent::behaviors();
    $behaviors['authenticator'] = [
      'class' => ApiAuth::class,
      'optional' => ['web-log-in']
    ];
    return $behaviors;
  }
  /*********************Channel模块控制层************************************/

  /**
   * 根据Id获取详情
   * @return mixed
   * @throws HttpException
   */
  public function actionWebLogIn()
  {

    if(parent::getUserCache()){
      $mpOpenId = parent::getMpOpenId();
      $userId = parent::getUserId();
    }else{
      $mpOpenId = '';
      $userId = '';
    }

    $phoneMode= Yii::$app->request->post("phoneMode");
    if (empty($mpOpenId)) {
      $mpOpenId = Yii::$app->request->post("openId");
    }


    if (!empty($mpOpenId)) {
      $userInfo = UserInfoModel::find()->select(['id', 'total_score','code', 'open_id', 'amount', 'telephone', 'sex', 'identify', 'source_id', 'source_json','nick_name','head_img','is_vip'])->where(['open_id' => $mpOpenId])->one();
    } else if (!empty($userId)) {
      $userInfo = UserInfoModel::find()->select(['id', 'total_score','code', 'open_id', 'amount', 'telephone', 'sex', 'identify', 'source_id', 'source_json','nick_name','head_img','is_vip'])->where(['id' => $userId])->one();

    }

    if (empty($userInfo)) {
      //渠道来源
      $sourceId = Yii::$app->session->get('sourceId');
      $ChannelModel =   ChannelModel::find()->where(['code'=>$sourceId])->one();

      if ($ChannelModel){
        $sourceId = $ChannelModel->id;
      }else{
        $ChannelModel =   ChannelModel::find()->one();
        $sourceId = $ChannelModel->id;
      }

      $userInfo = new  UserInfoModel();
      $userInfo->id = StringHelper::uuid();
      $userInfo->phone_mode=$phoneMode;
      if (!empty($mpOpenId)) {
        $userInfo->open_id = $mpOpenId;
      }


      $channel =$this->service->getOneById($sourceId);


      if (!empty($channel)) {
        $channel=ArrayHelper::toArray($channel);
        $userInfo->source_id = $channel['id'];
        $userInfo->total_score = $channel['score'];
        $userInfo->source_json = json_encode(['url' => $channel['short_url'], 'name' => $channel['name'], 'brand' => $channel['brand']], JSON_UNESCAPED_UNICODE);
      }

      $userInfo->last_log_in_ip = UserHostIp::getIP();
      $userInfo->last_log_in_at = time();
      $userInfo->nick_name = '';
      $userInfo->head_img = '';
      $userInfo->is_vip = '';


      $userInfo->insert();
    } else {

      $channel =$this->service->getHtmlOneById($userInfo['source_id']);

    }

    $userResult = new UserInfoResult();

    if (!empty($userInfo)) {
      $userResult->channel = $channel;
      unset($userInfo->source_json);
      $token = $this->loginService->createAuthKey($userInfo, NumberEnum::TEN_DAYS);
      $userResult->token = $token;
      $userResult->userInfo = $userInfo;
    }

    return $userResult;
  }

  /**
   * 根据Id获取详情
   * @return mixed
   * @throws HttpException
   */
  public
  function actionGetById()
  {
    $array = $this->service->getOneById(parent::getRequestId());

    return $array;
  }

  /**
   * 根据Id更新
   * @return mixed
   * @throws HttpException
   */
  public
  function actionUpdateById()
  {
    $model = new ChannelModel(['scenario' => 'update']);
    $model->setAttributes($this->getRequestPost(false));
    if ($model->validate()) {
      if (isset($model->full_url)) {
        if (strpos($model->full_url, 'sourceId=') === false) {
          if (strpos($model->full_url, '?') !== false) {
            $model->full_url .= "&sourceId=" . $model->id;
          } else {
            $model->full_url .= "?sourceId=" . $model->id;
          }
        }
      }
      return $this->service->updateChannelById($model);
    } else {
      throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, parent::getModelError($model));
    }
  }

  /**
   * 根据Id删除
   * @return mixed
   * @throws HttpException
   */
  public function actionDelById()
  {
    return $this->service->deleteById(parent::getRequestId());
  }
}
/**********************End Of Channel 控制层************************************/ 



<?php

namespace api\modules\mobile\controllers;

use api\modules\auth\ApiAuth;
use api\modules\mobile\models\forms\UserScoreDetailModel;
use api\modules\mobile\models\forms\UserScoreDetailQuery;
use api\modules\mobile\service\SystemGroupService;
use api\modules\mobile\service\UserInfoService;
use api\modules\mobile\service\UserScoreDetailService;
use api\modules\mobile\service\UserScoreService;
use api\modules\seller\models\forms\ChannelModel;
use api\modules\seller\service\ChannelService;
use fanyou\components\SystemConfig;
use fanyou\components\systemDrive\ArrayColumn;
use fanyou\enums\entity\ScoreTypeEnum;
use fanyou\enums\HttpErrorEnum;
use fanyou\enums\StatusEnum;
use fanyou\enums\SystemConfigEnum;
use fanyou\error\errorCode\ErrorUser;
use fanyou\error\FanYouHttpException;
use fanyou\service\FanYouSystemGroupService;
use fanyou\tools\ArrayHelper;
use Yii;
use yii\web\HttpException;

/**
 *  UserScore
 * @author  Round
 * @E-mail: Administrator@qq.com
 *
 */
class UserScoreController extends BaseController
{

    private $service;
    private $systemConfig;
    private $scoreService;
    private $scoreDetailService;
    private $groupService;

    public function init()
    {
        parent::init();
        $this->service = new UserInfoService();
        $this->systemConfig = new SystemConfig();
        $this->scoreService = new UserScoreService();
        $this->scoreDetailService = new UserScoreDetailService();
        $this->groupService = new SystemGroupService();
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => ApiAuth::class,
            'optional' => ['get-score-config', 'get-sign-score']
        ];
        return $behaviors;
    }
    /***********************用户积分*******************************/
    /**
     * 签到领取积分
     * @return mixed
     * @throws HttpException
     */
    public function actionSignScore()
    {
        $model = new UserScoreDetailModel();
        $model->setAttributes($this->getRequestPost());
        $model->type = ScoreTypeEnum::SIGN;
        $model->is_deduct = StatusEnum::COME_IN;
        if ($model->validate()) {
            if ($this->scoreService->isAlreadySign($model->user_id)) {
                throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, ErrorUser::USER_ALREADY_SING);
            }
            return $this->scoreDetailService->addUserScoreDetail($model);
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, parent::getModelError($model));
        }
    }

    /**
     * 获取积分配置
     * @return mixed
     */
    public function actionGetScoreConfig()
    {
        $tokenId = "html_score_config";
        $tokenContent = Yii::$app->cache->get($tokenId);
        if (!empty($tokenContent)) {
            return json_decode($tokenContent);
        }
        $model = $this->systemConfig->getConfigInfo(true, SystemConfigEnum::SCORE_CONFIG, StatusEnum::GROUP);
        $array = ArrayColumn::getSystemConfigValue($model);
        if (isset($array['sign_config'])) {
            $groupInfo = $this->groupService->getOneById($array['sign_config']);
            $gValues = FanYouSystemGroupService::getSystemGroupDate($groupInfo, false);
            //缓存300秒，过期重获 资源
            $item_arr = $gValues['items'];
            foreach ($item_arr as $key=>&$row){
              $row['day'] = date('m.d',time()+24*3600*$key);
            }

            Yii::$app->cache->set($tokenId, json_encode($item_arr), 300);
            return $item_arr;
        }

        return [];
    }

    /**
     * 获取用户签到积分
     * @return mixed
     */
    public function actionGetSignScore()
    {
        return ArrayHelper::toArray($this->scoreService->getOneById(parent::getUserId()));
    }

    /**
     * 获取详情列表
     * @return mixed
     */
    public function actionGetDetailPage()
    {
        $query = new UserScoreDetailQuery();
        $query->setAttributes($this->getRequestGet(), false);
        return $this->scoreDetailService->getUserScoreDetailPage($query);
    }

    /**
     *
     * @return mixed
     */
    public function actionUserScore()
    {
        return $this->scoreService->getUserScore(parent::getUserId());
    }

}
/**********************End Of 控制层************************************/



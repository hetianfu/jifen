<?php

namespace api\modules\seller\controllers;

use api\modules\auth\ApiAuth;
use api\modules\seller\models\forms\ProductModel;
use api\modules\seller\service\AppVersionService;
use fanyou\components\SystemConfig;
use fanyou\enums\entity\StrategyTypeEnum;
use fanyou\enums\HttpErrorEnum;
use fanyou\enums\SystemConfigEnum;
use fanyou\error\FanYouHttpException;
use fanyou\service\GatherProductService;
use fanyou\tools\ArrayHelper;

/**
 * 订单语音提醒
 * Class VoiceController
 * @package api\modules\seller\controllers
 */
class VoiceController extends BaseController
{

    private $service;
    private $configService;

    public function init()
    {
        parent::init();
        $this->service = new AppVersionService();
        $this->configService = new SystemConfig();
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => ApiAuth::class,
        ];
        return $behaviors;
    }
    /*********************AppVersion模块控制层************************************/
    /**
     * 请求数据转换成商品对象
     * @param $data
     * @return mixed
     */
    public function actionGetDetail()
     {

    }

}
/**********************End Of AppVersion 控制层************************************/ 



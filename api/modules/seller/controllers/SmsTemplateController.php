<?php

namespace api\modules\seller\controllers;

use api\modules\auth\ApiAuth;
use api\modules\seller\models\forms\BasicOrderInfoModel;
use api\modules\seller\models\forms\SmsLogModel;
use api\modules\seller\models\forms\SmsLogQuery;
use api\modules\seller\service\SmsLogService;
use api\modules\seller\service\SmsTemplateService;
use api\modules\seller\models\forms\SmsTemplateModel;
use api\modules\seller\models\forms\SmsTemplateQuery;
use fanyou\error\FanYouHttpException;
use fanyou\enums\HttpErrorEnum;
use fanyou\service\ThirdSmsService;
use fanyou\tools\ArrayHelper;
use Yii;
use yii\web\HttpException;

/**
 * Class SmsTemplateController
 * @package api\modules\seller\controllers
 * @author: HenryJia
 * @E-mail: 316560925@qq.com
 * @date: 2020-11-16
 */
class SmsTemplateController extends BaseController
{

    private $service;
    private $logService;

    public function init()
    {
        parent::init();
        $this->service = new SmsTemplateService();

        $this->logService = new SmsLogService();
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => ApiAuth::class,
        ];
        return $behaviors;
    }
    /*********************SmsTemplate模块控制层************************************/
    /**
     * 添加
     * @return mixed
     * @throws HttpException
     */
    public function actionAdd()
    {
        $model = new SmsTemplateModel();
        $model->setAttributes($this->getRequestPost());
        if ($model->validate()) {
            $model->sign_name = trim($model->sign_name);
            return $this->service->addSmsTemplate($model);
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, parent::getModelError($model));
        }
    }

    /**
     * 分页获取列表
     * @return mixed
     * @throws HttpException
     */
    public function actionGetPage()
    {
        $query = new SmsTemplateQuery();
        $query->setAttributes($this->getRequestGet());
        if ($query->validate()) {
            return $this->service->getSmsTemplatePage($query);
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, parent::getModelError($query));
        }
    }


    /**
     * 根据Id获取详情
     * @return mixed
     * @throws HttpException
     */
    public function actionGetById()
    {
        return $this->service->getOneById(parent::getRequestId());
    }

    /**
     * 根据Id更新
     * @return mixed
     * @throws HttpException
     */
    public function actionUpdateById()
    {

        $model = new SmsTemplateModel(['scenario' => 'update']);
        $model->setAttributes($this->getRequestPost(false));
        if ($model->validate()) {
            $model->sign_name = trim($model->sign_name);
            return $this->service->updateSmsTemplateById($model);
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

    /**
     * 分页获取列表
     * @return mixed
     * @throws HttpException
     */
    public function actionGetLogPage()
    {
        $query = new SmsLogQuery();
        $query->setAttributes($this->getRequestGet());
        if ($query->validate()) {
            return $this->logService->getSmsLogPage($query);
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, parent::getModelError($query));
        }
    }

    /**
     * 重新发送
     * @return mixed
     * @throws HttpException
     */
    public function actionLogReSend()
    {
        $id = parent::postRequestId();
        $ids = explode(',', $id);
        if (count($ids) && !empty($_ENV['THIRD_SMS_STATUS'])) {
            $model = $this->logService->getOneById($id);
            ThirdSmsService::batchSendSms($model['content'], $model['telephone'],$model['sms_type'],$id);
        }
        return count($ids);
    }
}
/**********************End Of SmsTemplate 控制层************************************/ 



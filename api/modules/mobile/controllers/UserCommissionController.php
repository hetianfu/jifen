<?php

namespace api\modules\mobile\controllers;

use api\modules\auth\ApiAuth;
use api\modules\mobile\models\forms\UserCommissionDetailQuery;
use api\modules\mobile\service\UserCommissionDetailService;
use api\modules\mobile\service\UserCommissionService;
use fanyou\enums\HttpErrorEnum;
use fanyou\enums\QueryEnum;
use fanyou\enums\StatusEnum;
use fanyou\enums\WalletStatusEnum;
use fanyou\error\FanYouHttpException;
use fanyou\tools\DaysTimeHelper;
use yii\web\HttpException;

/**
 * Class UserController
 * @package api\modules\mobile\controllers
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-06-09 17:46
 */
class UserCommissionController extends BaseController
{
    private $commissionService;
    private $commissionDetailService;

    public function init()
    {
        parent::init();

        $this->commissionService=new UserCommissionService();
        $this->commissionDetailService = new UserCommissionDetailService();
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => ApiAuth::class,
            'optional'=>['come-in-this-month'],

        ];
        return $behaviors;
    }
    /*********************UserInfo模块控制层************************************/


    /**
     * 佣金
     * @return mixed
     */
    public function actionGet()
    {
      return   $this->commissionService->getOneById(parent::getUserId());

    }

    /**
     * 本月入帐
     * @return mixed
     * @throws FanYouHttpException
     */
    public function actionComeInThisMonth()
    {
        $query = new UserCommissionDetailQuery();
        $query->setAttributes($this->getRequestGet());
        if ($query->validate()) {
            $query->type=QueryEnum::IN.WalletStatusEnum::EFFECT_IN;

            $query->created_at=QueryEnum::GE.DaysTimeHelper::thisMonth(true)['start'];

            return $this->commissionDetailService->sumCommission($query);
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, parent::getModelError($query));
        }
    }

    /**
     * 提现记录
     * @return mixed
     * @throws HttpException
     */
    public function actionGetDrawDetailPage()
    {
        $query = new UserCommissionDetailQuery();
        $query->setAttributes($this->getRequestGet(),false);
        if ($query->validate()) {
            $query->type=WalletStatusEnum::DRAW;
            return $this->commissionDetailService->getUserCommissionDetailPage($query);
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, parent::getModelError($query));
        }
    }

    /**
     * 佣金记录
     * @return mixed
     * @throws HttpException
     */
    public function actionGetDetailPage()
    {
        $query = new UserCommissionDetailQuery();
        $query->setAttributes($this->getRequestGet(),false);
        if ($query->validate()) {

            $query->type=QueryEnum::IN.WalletStatusEnum::EFFECT_IN;

            return $this->commissionDetailService->getUserCommissionDetailPage($query);
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, parent::getModelError($query));
        }
    }

    /**
     * 获取下家的佣金列表
     * @return mixed
     * @throws FanYouHttpException
     */
    public function actionGetDiscipleDetailPage()
    {
        $query = new UserCommissionDetailQuery();
        $query->setAttributes($this->getRequestGet());
        if ($query->validate()) {

            $query->status=StatusEnum::SUCCESS;
            $query->type=QueryEnum::IN.WalletStatusEnum::EFFECT_IN;

            return $this->commissionDetailService->getUserCommissionDetailPage($query);
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, parent::getModelError($query));
        }
    }

}
/**********************End Of UserInfo 控制层************************************/ 



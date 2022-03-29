<?php

namespace api\modules\seller\controllers;

use api\modules\auth\ApiAuth;
use api\modules\seller\models\event\OrderEvent;
use api\modules\seller\models\forms\UserCommissionDetailModel;
use api\modules\seller\models\forms\UserCommissionDetailQuery;
use api\modules\seller\service\UserCommissionDetailService;
use fanyou\enums\entity\OrderStatusEnum;
use fanyou\enums\HttpErrorEnum;
use fanyou\enums\NumberEnum;
use fanyou\enums\QueryEnum;
use fanyou\enums\StatusEnum;
use fanyou\enums\WalletStatusEnum;
use fanyou\error\FanYouHttpException;
use yii\web\HttpException;

/**
 * Class UserCommissionDetailController
 * @package api\modules\seller\controllers
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-06-05 16:43
 */
class UserDrawApplyController extends BaseController
{

    private $service;
    const EVENT_DRAW_CASH= 'draw_cash';
    public function init()
    {
        parent::init();
        $this->service = new UserCommissionDetailService();

        $this->on(self::EVENT_DRAW_CASH, ['api\modules\seller\service\event\MessageEventService', 'drawCashMessage']);

    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => ApiAuth::class,
            'optional'=>['sum']
        ];
        return $behaviors;
    }
    /*********************UserCommissionDetail模块控制层************************************/
    /**
     * 统计数据
     * @return mixed
     * @throws HttpException
     */
    public function actionSum()
    {
        $query = new UserCommissionDetailQuery();
        $query->setAttributes($this->getRequestGet());
        if ($query->validate()) {
            $query->type=QueryEnum::IN. WalletStatusEnum::DRAW.','.WalletStatusEnum::MP_DRAW;
            $query->status=null;
            return $this->service->sum($query);
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, parent::getModelError($query));
        }
    }
    /**
     * 分页获取列表
     * @return mixed
     * @throws HttpException
     */
    public function actionGetPage()
    {
        $query = new UserCommissionDetailQuery();
        $query->setAttributes($this->getRequestGet());
        if ($query->validate()) {
            $query->type=QueryEnum::IN. WalletStatusEnum::DRAW.','.WalletStatusEnum::MP_DRAW;
            $result=$this->service->getUserCommissionDetailPage($query);
            $header=$this->service->sum($query);
            foreach ($header as $k=>$item){
              $header[$k]['amount']=$item['amount']*NumberEnum::N_ONE;
            }
            $result['header']= $header;
            return $result;

        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, parent::getModelError($query));
        }
    }
    /**
     * 根据Id获取详情
     * @return mixed
     */
    public function actionGetById()
    {
        return $this->service->getOneById(parent::getRequestId());
    }

    /**
     * 同意提现
     * @return mixed
     * @throws HttpException
     */
    public function actionApproveById()
    {
        $result=0;
        $model = new UserCommissionDetailModel(['scenario' => 'update']);
        $model->setAttributes($this->getRequestPost(false));
        $model->status = StatusEnum::APPROVE;

        if ($model->validate()) {

            $old = $this->service->verifyApplyDraw($model);
            if (!empty($old)) {
                $result = $this->service->approveDraw($old);
                if ($result) {
                    $this->service->updateUserCommissionDetailById($model) ;
                }
            }
            if($result){
                $event=new OrderEvent();
                $event->id= $old['open_id'];
                $event->number= $old['amount'] ;
                $event->success=StatusEnum::SUCCESS;
                $this->trigger(self::EVENT_DRAW_CASH,$event);
            }
            return $result;
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, parent::getModelError($model));
        }
    }

    /**
     * 禁用提现
     * @return mixed
     * @throws HttpException
     */
    public function actionForbidById()
    {
        $model = new UserCommissionDetailModel(['scenario' => 'update']);
        $model->setAttributes($this->getRequestPost(false));

        $model->status = StatusEnum::FORBID;
        if ($model->validate()) {
            $result=$this->service->updateUserCommissionDetailById($model);
            if($result){
                $old = $this->service->getOneById($model->id);
                $event=new OrderEvent();
                $event->id= $old['open_id'];
                $event->number= $old['amount'] ;
                $event->success= StatusEnum::FAIL;
                $this->trigger(self::EVENT_DRAW_CASH,$event);
            }
            return $result;
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, parent::getModelError($model));
        }
    }


}
/**********************End Of UserCommissionDetail 控制层************************************/ 



<?php

namespace api\modules\seller\controllers;

use api\modules\auth\ApiAuth;
use api\modules\seller\service\UserInfoService;
use api\modules\seller\models\forms\DistributeUserModel;
use api\modules\seller\models\forms\DistributeUserQuery;
use api\modules\seller\models\forms\UserInfoModel;
use api\modules\seller\service\DistributeUserService;
use fanyou\enums\HttpErrorEnum;
use fanyou\enums\StatusEnum;
use fanyou\error\FanYouHttpException;
use yii\web\HttpException;

/**
 * Class DistributeUserController
 * @package api\modules\seller\controllers
 * @author: HenryJia
 * @E-mail: 316560925@qq.com
 * @date: 2020-09-01
 */
class DistributeUserController extends BaseController
{

    private $service;
    private $userService;

    public function init()
    {
        parent::init();
        $this->service = new DistributeUserService();
        $this->userService = new UserInfoService();
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => ApiAuth::class,
            'optional' => ['get-partner-page', 'get-page']
        ];
        return $behaviors;
    }
    /*********************DistributeUser模块控制层************************************/
    /**
     * 添加
     * @return mixed
     * @throws HttpException
     */
    public function actionAdd()
    {
        $model = new UserInfoModel();
        $model->setAttributes($this->getRequestPost());
        if ($model->validate()) {
            return $this->service->addDistributeUser($model);
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
        $query = new DistributeUserQuery();
        $query->setAttributes($this->getRequestGet());
        if ($query->validate()) {
            $list = $this->service->getDistributeUserPage($query);
            if (count($list['list'])) {
                foreach ($list['list'] as $k => $v) {
                    $list['list'][$k]['partner'] = intval($this->userService->countDisciples($v['id']));
                }
            }
            return $list;
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, parent::getModelError($query));
        }
    }

    /**
     * 下级小伙伴
     * @return mixed
     */
    public function actionGetPartnerPage()
    {
        $parentId = parent::getRequestId('parentId');
        $list = $this->userService->getDiscipleList($parentId);
        if (count($list)) {
            foreach ($list as $k => $v) {
                $list[$k]['partner'] = intval($this->userService->countDisciples($v['id']));
            }
        }
        return $list;
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

        $model = new DistributeUserModel(['scenario' => 'update']);
        $model->setAttributes($this->getRequestPost(false));
        if ($model->validate()) {
            return $this->service->updateDistributeUserById($model);
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
        $model = new UserInfoModel();
        $model->id = parent::getRequestId();
        $model->identify = StatusEnum::STATUS_INIT;
        return $this->service->addDistributeUser($model);
    }
}
/**********************End Of DistributeUser 控制层************************************/ 



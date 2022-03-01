<?php

namespace api\modules\seller\controllers;

use api\modules\auth\ApiAuth;
use api\modules\seller\models\forms\PayConfigModel;
use api\modules\seller\models\forms\PayConfigQuery;
use api\modules\seller\service\PayConfigService;
use fanyou\enums\HttpErrorEnum;
use fanyou\error\FanYouHttpException;
use fanyou\tools\StringHelper;
use Yii;
use yii\base\BaseObject;
use yii\web\HttpException;


/**
 * ShopController
 * @author E-mail: Administrator@qq.com
 * Create Time: 2019/03/06
 */
class PayConfigController extends BaseController
{
    private $service;
    public function init()
    {
        parent::init();
        $this->service = new PayConfigService();

    }
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => ApiAuth::class,
            'optional' => ['get-auth-key','get-auth-info','get-pay-by-id']
        ];
        return $behaviors;
    }

    /**
     * 添加支付配置
     * @return mixed
     * @throws \Exception
     */
    public function actionAddPayConfig()
    {
      $model=new PayConfigModel();

      $model->setAttributes($this->getRequestPost());
      if ($model->validate()) {
        return $this->service->add($model);
      } else {
        throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, parent::getModelError($model));
      }
    }

    /**
     * 删除门店
     * @return mixed
     */
    public function actionDelPayById()
    {
        return $this->service->delPayById(parent::getRequestId());
    }

    /**
     * 获取信息
     * @return mixed
     */
    public function actionGetPayById()
    {
        return $this->service->getPayInfoById(parent::getRequestId());
    }

    /**
     * 列表
     * @return mixed
     * @throws FanYouHttpException
     */
    public function actionGetPayPage()
    {
        $query = new PayConfigQuery();
        $query->setAttributes($this->getRequestGet(),false);

        if ($query->validate()) {
            $query->status = 0;
            return $this->service->getPageList($query);
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($query));
        }
    }
    /**
     * 更新详情
     * @return mixed
     * @throws HttpException
     */
    public function actionUpdatePayById()
    {

        $updatePayConfigModel = new PayConfigModel(['scenario' => 'update']);

      $updatePayConfigModel->setAttributes($this->getRequestPost(false),false);
      $updatePayConfigModel->id=parent::postRequestId();
        if ($updatePayConfigModel->validate()) {
                return $this->service->updateById($updatePayConfigModel);
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($updatePayConfigModel));
        }
    }

}
/**********************End Of ShopBasic 控制层************************************/ 
 


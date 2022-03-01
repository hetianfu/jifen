<?php

namespace api\modules\seller\controllers;

use api\modules\auth\ApiAuth;
use api\modules\seller\models\forms\ShopInfoModel;
use api\modules\seller\models\forms\ShopInfoQuery;
use api\modules\seller\service\ShopService;
use fanyou\enums\HttpErrorEnum;
use fanyou\error\FanYouHttpException;
use fanyou\tools\StringHelper;
use Yii;
use yii\web\HttpException;


/**
 * ShopController
 * @author E-mail: Administrator@qq.com
 * Create Time: 2019/03/06
 */
class ShopController extends BaseController
{
    private $service;
    public function init()
    {
        parent::init();
        $this->service = new ShopService();

    }
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => ApiAuth::class,
            'optional' => ['get-auth-key','get-auth-info','get-shop-by-id']
        ];
        return $behaviors;
    }

    /**
     * 添加门店
     * @return mixed
     * @throws \Exception
     */
    public function actionAddShop()
    {   $model=new ShopInfoModel();

        $model->attributes = $this->getRequestPost();
        $model->id=StringHelper::uuid();
        return $this->service->addShop($model);
    }

    /**
     * 删除门店
     * @return mixed
     */
    public function actionDelShopById()
    {
        return $this->service->delShopById(parent::getRequestId());
    }

    /**
     * 获取店铺信息
     * @return mixed
     */
    public function actionGetShopById()
    {
        return $this->service->getShopInfoById(parent::getRequestId());
    }

    /**
     * 门店列表
     * @return mixed
     * @throws FanYouHttpException
     */
    public function actionGetShopPage()
    {
        $query = new ShopInfoQuery();
        $query->setAttributes($this->getRequestGet(),false);

        if ($query->validate()) {
            return $this->service->getPageList($query);
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($query));
        }
    }
    /**
     * 更新店铺详情
     * @return mixed
     * @throws HttpException
     */
    public function actionUpdateShopById()
    {

        $updateShopInfoModel = new ShopInfoModel(['scenario' => 'update']);

        $updateShopInfoModel->setAttributes($this->getRequestPost(false),false);
        $updateShopInfoModel->id=parent::postRequestId();
        if ($updateShopInfoModel->validate()) {
                return $this->service->updateById($updateShopInfoModel);
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($updateShopInfoModel));
        }
    }

}
/**********************End Of ShopBasic 控制层************************************/ 
 


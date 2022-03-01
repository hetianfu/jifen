<?php

namespace api\modules\seller\controllers;

use api\modules\auth\ApiAuth;
use api\modules\seller\service\ShopDepartmentService;
use api\modules\seller\models\forms\ShopDepartmentModel;
use api\modules\seller\models\forms\ShopDepartmentQuery;
use api\modules\seller\models\ShopDepartmentResult;

use Yii;
use yii\web\HttpException;

/**
 * zl_shop_department
 * @author E-mail: Administrator@qq.com
 * Create Time: 2019/04/24
 */
class ShopDepartmentController extends BaseController
{

    private $service;

    public function init()
    {
        parent::init();
        $this->service = new ShopDepartmentService();
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => ApiAuth::class,
        ];
        return $behaviors;
    }
    /*********************ShopDepartment模块控制层************************************/
    /**
     * 添加一条内容
     * @return mixed
     * @throws HttpException
     */
    public function actionAddShopDepartment()
    {
        $model = new ShopDepartmentModel();
        $model->setAttributes($this->getRequestPost());
        if ($model->validate()) {
            return $this->service->addShopDepartment($model);
        } else {
            throw new HttpException(417, parent::getModelError($model));
        }
    }

    /**
     * 获取店铺所有部门列表
     * @return mixed
     */
    public function actionGetShopDepartmentList()
    {
        $queryShopDepartment = new ShopDepartmentQuery();
        $queryShopDepartment->attributes = $this->getRequestGet();
        return $this->service->getShopDepartmentList($queryShopDepartment);
    }
    /**
     * 根据Id获取单条对象
     * @return mixed
     * @throws HttpException
     */
    public function actionGetShopDepartmentById()
    {
        $id = Yii::$app->request->get('id');
        return $this->service->getShopDepartmentById($id);
    }

    /**
     * 更新对象
     * @return mixed
     * @throws HttpException
     */
    public function actionUpdateShopDepartment()
    {

        $updateModel = new ShopDepartmentModel(['scenario' => 'update']);

        $updateModel->setAttributes($this->getRequestPost());
        if ($updateModel->validate()) {
            return $this->service->getShopDepartmentById($updateModel);
        } else {
            throw new \yii\web\UnprocessableEntityHttpException(parent::getModelError($updateModel));
        }
    }

    /**
     * 根据Id删除对象
     * @return mixed
     * @throws HttpException
     */
    public function actionDelShopDepartment()
    {
        $id = Yii::$app->request->get('id');
        return $this->service->deleteShopDepartment($id);

    }
}
/**********************End Of ShopDepartment 控制层************************************/ 
 


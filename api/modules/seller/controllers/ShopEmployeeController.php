<?php

namespace api\modules\seller\controllers;

use api\modules\auth\ApiAuth;
use api\modules\seller\models\forms\ShopEmployeeModel;
use api\modules\seller\models\forms\ShopEmployeeQuery;
use api\modules\seller\service\AuthAssignmentService;
use api\modules\seller\service\ShopEmployeeService;

use fanyou\enums\entity\AuthItemEnum;
use fanyou\enums\HttpErrorEnum;
use fanyou\enums\QueryEnum;
use fanyou\error\FanYouHttpException;
use Yii;
use yii\web\HttpException;

/**
 * zl_shop_employee
 * @author E-mail: Administrator@qq.com
 * Create Time: 2019/04/24
 */
class ShopEmployeeController extends BaseController
{

    private $service;
    private $assignService;

    public function init()
    {
        parent::init();
        $this->service = new ShopEmployeeService();
        $this->assignService = new AuthAssignmentService();
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => ApiAuth::class,
        ];
        return $behaviors;
    }
    /*********************ShopEmployee模块控制层************************************/
    /**
     * 添加一条内容
     * @return mixed
     * @throws HttpException
     */
    public function actionAddShopEmployee()
    {
        $model = new ShopEmployeeModel();
        $model->setAttributes($this->getRequestPost(false));

        $model->merchant_id =Yii::$app->user->identity['merchantId'];
        $model->shop_id=parent::postRequestId('shopId');
        if ($model->validate()) {
            if(empty($model->password)){
                $model->password='e10adc3949ba59abbe56e057f20f883e';
            }
            $id = $this->service->addShopEmployee($model);
            if (!empty($id)) {
                $params = Yii::$app->request->post(AuthItemEnum::ROLELIST)  ;
                $params&& $this->assignService->addAuthAssignment($params, $id);
            }
            return $id;
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, parent::getModelError($model));
        }
    }

    /**
     * 分页获取对象列表
     * @return mixed
     * @throws HttpException
     */
    public function actionGetShopEmployeePage()
    {
        $queryShopEmployee = new ShopEmployeeQuery();
        $queryShopEmployee->attributes = $this->getRequestGet();
        $roleIdQuery=Yii::$app->request->get("roleId");
        if ($queryShopEmployee->validate()) {
            if(!empty($roleIdQuery)){
                $userIds= $this->assignService->findUserIdByRoleIdAndAppId($roleIdQuery);
                $queryShopEmployee-> id=QueryEnum::IN.implode(',',$userIds );
            }
            $result = $this->service->getShopEmployeePage($queryShopEmployee);
            if (count($result['list'])) {
                foreach ($result['list'] as $key => $item) {
                    $result['list'][$key]['roleList'] = $this->assignService->findAllByUserIdAndAppId($item['id']);
                }
            }
            return $result;
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, parent::getModelError($queryShopEmployee));
        }
    }

    /**
     * 根据Id获取单条对象
     * @return mixed
     * @throws HttpException
     */
    public function actionGetShopEmployeeById()
    {
        $id = Yii::$app->request->get('id');
        return $this->service->getShopEmployeeById($id);
    }


    /**
     * 更新对象
     * @return mixed
     * @throws HttpException
     */
    public function actionUpdateShopEmployee()
    {
        $updateModel = new ShopEmployeeModel(['scenario' => 'update']);
        $updateModel->setAttributes($this->getRequestPost(false));
        if ($updateModel->validate()) {
            $result = $this->service->updateShopEmployeeById($updateModel);
            if (!empty($result)) {

                $params = Yii::$app->request->post(AuthItemEnum::ROLELIST);
                if (!empty($params)) {
                    $this->assignService->addAuthAssignment($params, $updateModel->id);
                }
            }
            return $result;
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($updateModel));
        }
    }
    /**
     * 更新对象
     * @return mixed
     * @throws HttpException
     */
    public function actionUpdatePassword()
    {
        $updateModel = new ShopEmployeeModel(['scenario' => 'update']);
        $updateModel->setAttributes($this->getRequestPost(false));
        if ($updateModel->validate()) {
            $result = $this->service->updateShopEmployeeById($updateModel);
            return $result;
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($updateModel));
        }
    }
    /**
     * 清除店员与微信绑定关系
     *
     */
    public function actionCleanShopEmployee()
    {
        $storeInfo = Yii::$app->user->identity;
        $updateShopEmployeeModel = new ShopEmployeeModel(['scenario' => 'update']);
        $updateShopEmployeeModel->attributes = Yii::$app->request->post();
        $updateShopEmployeeModel->storeId = $storeInfo['storeId'];
        if ($updateShopEmployeeModel->validate()) {
            $this->sendLogWarning($updateShopEmployeeModel->toArray());
            return $this->service->cleanShopEmployee($updateShopEmployeeModel);
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($updateShopEmployeeModel));
        }
    }

    /**
     * 根据Id删除对象
     * @return mixed
     * @throws HttpException
     */
    public function actionDelShopEmployee()
    {
        $id = Yii::$app->request->get('id');
        $result = $this->service->deleteShopEmployee($id);
        if ($result) {
            $this->assignService->removeAuthAssignment($id);
        }
        return $result;

    }

    /**
     * 获取带参数的微信临时二维码
     * @return mixed
     */
    public function actionGetMpQrCode()
    {
        $id = Yii::$app->request->get('id');
        $redisKey = $this->service->saveToRedis($id);

        if (!empty($redisKey)) {

            return $this->service->createMpQrCode($redisKey);
        }

    }

    public function actionCleanEmployeeRedis()
    {
        $storeInfo = Yii::$app->user->identity;
        return $this->service->cleanEmployeeRedis($storeInfo['storeId']);
    }

    /**********************End Of ShopEmployee 控制层************************************/
    /**
     * 员工绑定的消息推送类型
     * 获取全部对象列表
     * @return mixed
     * @throws HttpException
     */
    public function actionGetEmployeeMsgTypeList()
    {
        $storeInfo = Yii::$app->user->identity;
        $id = Yii::$app->request->get('id');
        $storeId = $storeInfo['storeId'];

        return $this->service->getEmployeeMsgList($id, $storeId);

    }

    public function actionAddEmployeeMsgType()
    {
        $storeInfo = Yii::$app->user->identity;
        $id = Yii::$app->request->post('id');
        $msgType = Yii::$app->request->post('msgType');
        $storeId = $storeInfo['storeId'];
        $this->sendLogWarning($id);
        return $this->service->addEmployeeMsg($id, $storeId, $msgType);
    }

    public function actionDeleteEmployeeMsgType()
    {
        $storeInfo = Yii::$app->user->identity;
        $id = Yii::$app->request->get('id');
        $msgType = Yii::$app->request->get('msgType');
        $storeId = $storeInfo['storeId'];
        $this->sendLogWarning($id);
        return $this->service->deleteEmployeeMsg($id, $storeId, $msgType);

    }
}
/**********************End Of ShopEmployee 控制层************************************/ 
 


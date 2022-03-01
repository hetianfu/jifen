<?php

namespace api\modules\seller\controllers;

use api\modules\auth\ApiAuth;
use api\modules\seller\service\AuthAssignmentService;
use api\modules\seller\service\AuthItemChildService;
use api\modules\seller\service\AuthItemService;
use api\modules\seller\service\LoginService;
use api\modules\seller\service\ShopEmployeeService;
use fanyou\enums\EnvEnum;
use fanyou\enums\HttpErrorEnum;
use fanyou\error\errorCode\ErrorUser;
use fanyou\error\FanYouHttpException;
use fanyou\tools\ArrayHelper;
use Yii;
use yii\base\Exception;
use yii\web\HttpException;


/**
 * ShopController
 * @author E-mail: Administrator@qq.com
 * Create Time: 2019/03/06
 */
class LoginController extends BaseController
{
    private $service;
    private $employeeService;
    private $authAssignmentService;
    private $itemService;
    private $childService;

    public function init()
    {
        parent::init();

        $this->service = new LoginService();
        $this->employeeService = new ShopEmployeeService();
        $this->authAssignmentService = new AuthAssignmentService();
        $this->itemService = new AuthItemService();
        $this->childService = new AuthItemChildService();

    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => ApiAuth::class,
            'optional' => ['is-init','basic-config', 'get-auth-key', 'get-auth-info']
        ];
        return $behaviors;

    }

    /**
     * 是否初使化
     * @throws FanYouHttpException
     */
    public function actionIsInit()
    {
        if (!file_exists(Yii::getAlias('@root') . '/.env')) {

            throw new  FanYouHttpException(HttpErrorEnum::REDIRECT, '请初使化数据源！');
        }
        return ;
    }
    public function actionBasicConfig()
    {
        if (!file_exists(Yii::getAlias('@root') . '/.env')) {
           return [  EnvEnum::ADMIN_NAME=> '', EnvEnum::ADMIN_LOGO=>'' , EnvEnum::ADMIN_VERSION=>''];
        }
        return EnvEnum::getAdminConfig();
    }
    /**
     * 店铺登陆
     * @return mixed
     * @throws FanYouHttpException
     */
    public function actionGetAuthKey()
    {
        $username = parent::postRequestId('username');
        $password = parent::postRequestId('password');
        try {

            $employee = $this->employeeService->getUnique($username, $password);
        } catch (Exception $e) {
            if (!isset($_ENV['DB_DSN'])) {
                throw new  FanYouHttpException(HttpErrorEnum::REDIRECT, '未配置数据源！');
            };
        }
        if (empty($employee)) {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, ErrorUser::USER_LOGIN_ERROR);
        }
        $roleIds = $this->authAssignmentService->findAllByUserIdAndAppId($employee['id']);
        return $this->service->createAuthKey($employee, $roleIds, $employee['is_admin']);

    }

    /**
     * 通过token获取用户登陆信息
     * @return mixed
     * @throws HttpException
     */
    public function actionGetAuthInfo()
    {
        $loginHeader = Yii::$app->request->getHeaders();

        $array = $this->service->getAuthInfo($loginHeader['access-token']);
        if (empty($array)) {
            throw new FanYouHttpException(HttpErrorEnum::Expectation_Failed, "错误"); // $loginHeader->getFirstError(),
        }
        $result = ArrayHelper::toArray(json_decode($array));
        return $result;

    }


}
/**********************End Of ShopBasic 控制层************************************/ 
 


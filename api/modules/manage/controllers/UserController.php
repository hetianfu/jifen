<?php

namespace api\modules\manage\controllers;

use api\modules\auth\ApiAuth;
use api\modules\manage\models\CacheManage;
use api\modules\manage\models\event\OrderEvent;
use api\modules\manage\models\forms\ShopEmployeeModel;
use api\modules\manage\models\forms\UserCheckCodeQuery;
use api\modules\manage\service\LoginService;
use api\modules\manage\service\ShopEmployeeService;
use api\modules\manage\service\UserCheckCodeService;
use api\modules\seller\models\forms\UserInfoModel;
use api\modules\seller\service\UserInfoService;
use fanyou\enums\HttpErrorEnum;
use fanyou\error\FanYouHttpException;
use fanyou\tools\StringHelper;
use Yii;
use yii\web\HttpException;

/**
 * Class UserController
 * @package api\modules\manage\controllers
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-06-19 14:01
 */
class UserController extends BaseController
{
    private $checkService;
    private $userService;
    private $employeeService;
    private $loginService;
    const EVENT_CHECK_ORDER = 'check_order';

    public function init()
    {
        parent::init();

        $this->userService = new UserInfoService();
        $this->checkService = new UserCheckCodeService();
        $this->employeeService = new ShopEmployeeService();
        $this->loginService = new LoginService();

        $this->on(self::EVENT_CHECK_ORDER, ['api\modules\manage\service\event\MessageEventService', 'checkOrder']);
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => ApiAuth::class,
            'optional' => ['login', 'register'] // 'get-check-record-page','scan-code','verify-code'
        ];
        return $behaviors;
    }


    /**
     * 店铺登陆
     * @return mixed
     * @throws FanYouHttpException
     */
    public function actionLogin()
    {
        $unionId = Yii::$app->request->post('unionId');
        $openId = Yii::$app->request->post('openId');
        if (!empty($unionId)) {
            $userInfo = $this->userService->getOneByUnionId($unionId);
            if (empty($userInfo)) {
                $userInfo = $this->userService->getOneByOpenId($openId);
            }
        } else {
            $userInfo = $this->userService->getOneByOpenId($openId);
        }
        if ($userInfo == null) {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, '未绑定登陆！');
        }
        $employee = $this->employeeService->loginByUserId($userInfo['id']);
        if (empty($employee)) {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, '员工未绑定登陆！');
        }
        return $this->loginService->createAuthKey($employee,$userInfo);
    }

    /**
     * 员工注册
     * @return int
     * @throws FanYouHttpException
     */
    public function actionRegister()
    {
        $addFlag = true;
        $openId = Yii::$app->request->post('openId');
        $unionId = Yii::$app->request->post('unionId');
        $account = Yii::$app->request->post('account');
        $password = Yii::$app->request->post('password');

        $id = StringHelper::uuid();
        if (!empty($unionId)) {
            $userInfo = $this->userService->getOneByUnionId($unionId);
            if (!empty($userInfo)) {
                $addFlag = false;
                $updateUserInfo = new UserInfoModel();
                $updateUserInfo->id = $userInfo->id;
            } else {
                $userInfo = new UserInfoModel();
                $userInfo->id = $id;
            }
        } else {
            $userInfo = $this->userService->getOneByOpenId($openId);
            if (!empty($userInfo)) {
                if($userInfo->telephone!=$account){
                    throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, '帐户未绑定正确手机号！');
                }
            }else{
            //如果没有unionId则，以电话号码做为联通设计
            $userInfo = new UserInfoModel();
            $userInfo->id = $id;
            }
        }
        $userInfo->open_id = $openId;
        $userInfo->union_id = $unionId;
        $userInfo->telephone = $account;
        $userInfo->nick_name = Yii::$app->request->post('nickName');
        $userInfo->head_img = Yii::$app->request->post('headImg');

        $employee = $this->employeeService->getOneByTelephone($account);
        if (empty($employee)) {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, '帐号或密码错误！');
        }
        if (!empty($employee) && !empty($employee['open_id'])) {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, '已绑定商户！');
        }
        if (!empty($employee) && ($password !== $employee['password'])) {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, '密码或帐号错误！');
        }
        if($addFlag){
            $userId= $this->userService->addUserInfo($userInfo);
        }else{
            $this->userService->updateUserInfoById($updateUserInfo);
            $userId =$updateUserInfo->id;
        }
        return ShopEmployeeModel::updateAll(['open_id' => $openId, 'union_id' => $unionId, 'user_id' => $userId], ['id' => $employee['id']]);

    }

    /**
     * 通过token获取用户登陆信息
     * @return mixed
     * @throws HttpException
     */
    public function actionGetAuthInfo()
    {
        $loginHeader = Yii::$app->request->getHeaders();
        $array = $this->loginService->getAuthInfo($loginHeader['access-token']);
        if (empty($array)) {
            throw new FanYouHttpException(HttpErrorEnum::UNAUTHORIZED, "错误");
        }
        return json_decode($array);

    }
    /*********************UserInfo模块控制层************************************/

    /**
     * 分页获取列表
     * @return mixed
     * @throws HttpException
     */
    public function actionGetCheckRecordPage()
    {
        $query = new UserCheckCodeQuery();
        $query->setAttributes($this->getRequestGet());
        if ($query->validate()) {
            return $this->checkService->getRecordPage($query);
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, parent::getModelError($query));
        }
    }

    /**
     * 查看记录订单详情
     * @return mixed
     */
    public function actionGetCheckRecordById()
    {
        return $this->checkService->getRecordById(parent::getRequestId());
    }

    /**
     * 扫码-获取核销详情
     * @return mixed
     * @throws FanYouHttpException
     */
    public function actionScanCode()
    {
        $codeInfo = $this->checkService->getOneByBarCode(parent::getRequestId());
        if (empty($codeInfo)) {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, "核销码不存在");
        }
        return $codeInfo;
    }

    /**
     * 根据订单取一条核销详情
     * @return mixed
     * @throws FanYouHttpException
     */
    public function actionGetInfoByOrder()
    {
        $codeInfo = $this->checkService->getOneByBarOrder(parent::getRequestId());
        if (empty($codeInfo)) {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, "核销码不存在");
        }
        return $codeInfo;
    }

    /**
     * 较验二维码
     * @return mixed
     */
    public function actionVerifyCode()
    {
        $model = new CacheManage();
        $model->setAttributes(parent::getUserCache(), false);
        $result = $this->checkService->verifyBarCode(Yii::$app->request->post('id'), $model);
        if ($result) {
            $event = new OrderEvent();
            $event->id = $model->id;
            $this->trigger(self::EVENT_CHECK_ORDER, $event);
        }
        return $result;
    }

}
/**********************End Of UserInfo 控制层************************************/ 



<?php

namespace api\modules\mobile\controllers;

use api\modules\auth\ApiAuth;
use api\modules\mobile\models\forms\UserShopCartQuery;
use api\modules\mobile\models\UserInfoResult;
use api\modules\mobile\service\LoginService;
use api\modules\mobile\service\UserInfoService;
use api\modules\mobile\service\UserShopCartService;
use api\modules\mobile\service\WechatService;
use fanyou\enums\entity\MiniProgramEnum;
use fanyou\tools\ArrayHelper;
use Yii;

/**
 * UserInfo
 * @author  Round
 * @E-mail: Administrator@qq.com
 *
 */
class UserLoginController extends BaseController
{

    private $service;
    private $userService;
    private $shopCartService;
    private $loginService;

    public function init()
    {
        parent::init();
        $this->service = new WechatService();
        $this->userService = new UserInfoService();
        $this->shopCartService = new UserShopCartService();
        $this->loginService = new LoginService();
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => ApiAuth::class,
            'optional' => ['code-to-session', 'login-mini-program','decrypt-data']
        ];
        return $behaviors;
    }

    /*********************UserLoginController模块控制层************************************/

    public function actionCodeToSession()
    {
        $code = Yii::$app->request->get(MiniProgramEnum::CODE);

        return   base64_encode(json_encode($this->service->getMiniAppCodeToSession($code)));

    }

    /**
     * 登陆小程序
     * @return array
     */
    public function actionLoginMiniProgram()
    {
        $userResult = new UserInfoResult();
        $codeToSessionKey = Yii::$app->request->get(MiniProgramEnum::CODE2SESSION);
        $array = ArrayHelper::toArray(json_decode(base64_decode($codeToSessionKey)));

        $unionId = $array['unionid'] ;
        if (empty($unionId)) {
            $userInfo = $this->userService->getOneByMiniAppOpenId($array['openid']);
        }else{
            $userInfo = $this->userService->getOneByUnionId($unionId);
        }
        if (!empty($userInfo)) {
            $this->userService->updateLastLoginTime($userInfo['id']);

            $token = $this->loginService->createAuthKey($userInfo);
            $userResult->token = $token;
            $userResult->userInfo = $userInfo;
            $queryShopCart = new UserShopCartQuery();
            $queryShopCart->user_id = $userInfo->id;

            $userResult->shoppingCartCount =$this->shopCartService->getUserShopCartCount($userInfo->id);
        }
        return ArrayHelper::toArray($userResult);

    }

    public function actionDecryptData()
    {
        $codeToSessionKey = Yii::$app->request->get(MiniProgramEnum::CODE2SESSION);

        $array = ArrayHelper::toArray(json_decode(base64_decode($codeToSessionKey)));
        if(is_null($array['session_key'])){
           return;
        }
        $iv=Yii::$app->request->get('iv');
        $encryptedData=Yii::$app->request->get('encryptedData');

        return   Yii::$app->wechat->miniProgram->encryptor->decryptData($array ['session_key'] , $iv, $encryptedData);
    }

}
/**********************End Of UserLoginController控制层************************************/



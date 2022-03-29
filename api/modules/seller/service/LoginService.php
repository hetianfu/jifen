<?php

namespace api\modules\seller\service;

use api\modules\seller\models\CacheLogInfo;
use fanyou\enums\HttpErrorEnum;
use fanyou\error\FanYouHttpException;
use fanyou\tools\ArrayHelper;
use Yii;
use yii\web\HttpException;

/**
	 * @author E-mail: Administrator@qq.com
	 * Create Time: 2019/03/06
	 */
class LoginService
{
    private $merchantService;
    public function __construct()
    {
        $this->merchantService = new MerchantInfoService();

    }

    /**
     * 创建token
     * @param $employee
     * @param $roleIds
     * @param  $isAdmin
     * @return string
     * @throws FanYouHttpException
     * @throws \yii\base\Exception
     */
    public function createAuthKey($employee,  $roleIds , $isAdmin=0): string
    {
        $merchantInfo = $this->merchantService->getOneById($employee['merchant_id']);
        if (is_null($merchantInfo) || is_null($merchantInfo['status']) || $merchantInfo['status'] < 0) {
            throw new FanYouHttpException(HttpErrorEnum::UNAUTHORIZED , "店铺已过期或密码错误!");
        }

        $storeModel = new CacheLogInfo();
        $storeModel->setAttributes(ArrayHelper::toArray($employee), false);
        $array =  $storeModel->toArray();
        $array['name'] = $employee['name'];
        $array['merchantId'] = $employee['merchant_id'];
        if(!$isAdmin){
        $array['shopId'] = $employee['shop_id'];
        }
        $array['isAdmin'] = $isAdmin;
        $array['account'] = $employee['account'];
        $array['roleIds'] = $roleIds;
        $array['logo'] = $merchantInfo['logo'];

        $token = Yii::$app->getSecurity()->generateRandomString();
        // 加入IP 地址，及时间戳
        $clientIp =Yii::$app->request->getRemoteIP();
        if(!isset($array['settleTime'])){
            $array['settleTime'] = 0;
        }
        $array['clientIp'] = $clientIp;
        $array['timestamp'] = time();
        Yii::$app->cache->set($token, json_encode($array), 3200);
        return $token;
    }
    /**
     * 获取session
     * @param $token
     * @return array
     */
    public function getAuthInfo($token)
    {
        return  Yii::$app->cache->get($token);
    }
}
/**********************End Of ShopBasic 服务层************************************/ 


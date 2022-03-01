<?php

namespace api\modules\manage\service;

use api\modules\manage\models\CacheManage;
use fanyou\tools\ArrayHelper;
use fanyou\tools\StringHelper;
use Yii;

/**
	 * @author E-mail: Administrator@qq.com
	 * Create Time: 2019/03/06
	 */
class LoginService
{
    /**
     * 创建token
     * @param $employee
     * @return string
     * @throws \yii\base\Exception
     */
    public function createAuthKey($employee,$userInfo=[]): string
    {
        $storeModel = new CacheManage();
        $storeModel->setAttributes(StringHelper::toUnCamelize(ArrayHelper::toArray($employee))  , false);
        $storeModel->user_id=$userInfo['id'];
        $storeModel->open_id=$userInfo['open_id'];
        $array =  $storeModel->toArray();

        $token = Yii::$app->getSecurity()->generateRandomString();
        // 加入IP 地址，及时间戳
        $clientIp =Yii::$app->request->getRemoteIP();

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


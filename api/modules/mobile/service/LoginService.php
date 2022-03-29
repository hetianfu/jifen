<?php

namespace api\modules\mobile\service;

use api\modules\mobile\models\forms\UserInfoModel;
use common\utils\FuncHelper;
use fanyou\enums\NumberEnum;
use Yii;
use yii\web\HttpException;

/**
	 * @author E-mail: Administrator@qq.com
	 * Create Time: 2019/03/06
	 */
class LoginService
{

    /**
     * 创建token
     * @param $userInfo
     * @param $time
     * @return string
     * @throws HttpException
     * @throws \yii\base\Exception
     */
    public function createAuthKey(UserInfoModel $userInfo,$time=3200): ?string
    {
        if (is_null($userInfo) ) {
            return null;
        }
        $token = Yii::$app->getSecurity()->generateRandomString();
        // 加入IP 地址，及时间戳
        $clientIp =FuncHelper::getClientIp();
        $array=$userInfo->toArray();
        $array['head_img'] = $array['headImg'];
        $array['nick_name'] = $array['nickName'];
        $array['is_vip'] = isset($array['isVip']) ? $array['isVip'] : '';
        $array['timestamp'] = time();
        $array['clientIp'] = $clientIp;

        Yii::$app->cache->set($token, json_encode($array), $time);
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


    public function resetAuthKey($token,UserInfoModel $userInfo,$time=NumberEnum::TEN_DAYS): ?string
    {
        if (is_null($userInfo) ) {
            return null;
        }
        // 加入IP 地址，及时间戳
        $clientIp =FuncHelper::getClientIp();
        $array=$userInfo->toArray();
        $array['timestamp'] = time();
        $array['clientIp'] = $clientIp;
        Yii::$app->cache->set($token, json_encode($array), $time);
        return $token;
    }
}
/**********************End Of ShopBasic 服务层************************************/ 


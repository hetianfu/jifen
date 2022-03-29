<?php

namespace api\modules\auth;


use common\utils\FuncHelper;
use fanyou\enums\HttpErrorEnum;
use fanyou\error\FanYouHttpException;
use Yii;

class User
{
    public  $identity;
    /**
     *
     * @param $token
     * @param null $type
     * @return mixed|null
     */
    public function loginByAccessToken($token, $type = null)
    {

        $userInfoJson = Yii::$app->cache->get($token);
        if($userInfoJson){
            $userInfo = json_decode($userInfoJson,true);
            if(isset($userInfo['storeList'])){
                $getStoreId = isset($_GET['storeId']) ? $_GET['storeId'] : null;
                $postStoreId = isset($_POST['storeId']) ? $_POST['storeId'] :null;
                if($getStoreId || $postStoreId){
                    $storeId = empty($getStoreId) ? $postStoreId : $getStoreId;
                    if(!isset($userInfo['storeList'][$storeId])){
                        return null;
                    }
                }
             }
            $clientIp = FuncHelper::getClientIp();



            //  if($clientIp == $userInfo['clientIp'] && ( time() - $userInfo['timestamp'] ) < 2600 ){
            if(($userInfo['timestamp'] - time() ) > 36000 ){
                $this->identity =$userInfo;
                return true;
                //   } else if ($clientIp == $userInfo['clientIp'] &&  ( time() - $userInfo['timestamp'] ) < 3200 ){
            } else if (( $userInfo['timestamp'] - time() ) < 36000 ){
                $userInfo['timestamp'] = time() + 86400;
                Yii::$app->cache->set($token, json_encode($userInfo, JSON_UNESCAPED_UNICODE), 86400);
                $this->identity =$userInfo;
                return true;
            }
        }
      throw new FanYouHttpException(HttpErrorEnum::UNAUTHORIZED,'请先登录');
    }

    public function getIdentity()
    {
        return null;
    }
}

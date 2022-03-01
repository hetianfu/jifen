<?php

namespace fanyou\tools\helpers;

use fanyou\components\SystemConfig;
use fanyou\enums\entity\BasicConfigEnum;
use fanyou\enums\HttpErrorEnum;
use fanyou\enums\SystemConfigEnum;
use fanyou\error\FanYouHttpException;
use Yii;
use yii\helpers\BaseUrl;

/**
 * Class Url
 * @package common\helpers
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-06-20 15:40
 */
class Url extends BaseUrl
{


    /**
     * 生成oauth2链接
     * @param bool $force
     * @return string
     * @throws FanYouHttpException
     */
    public static function toOAuth2($force=false)
    {
        $configService=new SystemConfig();
        $basicConfig=$configService->getConfigInfoValue($force,SystemConfigEnum::BASIC_CONFIG);
        if(is_null($basicConfig)){
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,"请配置正常的后台域名地址");
        }
        $domainName = Yii::getAlias('@oauth2Url');
        return  $basicConfig[BasicConfigEnum::BASIC_SITE]. $domainName ;
    }

    /**
     * 生成OAuth2回调链接
     * @param bool $force
     * @return string
     * @throws FanYouHttpException
     */
    public static function toOAuth2Redirect($force=false)
    {
        $domainName = Yii::getAlias('@oauth2RedirectUrl');
        $configService=new SystemConfig();
        $basicConfig=$configService->getConfigInfoValue($force,SystemConfigEnum::BASIC_CONFIG);
        if(is_null($basicConfig)){
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,"请配置正常的后台域名地址");
        }
        return  $basicConfig[BasicConfigEnum::BASIC_SITE]. $domainName ;
    }


}
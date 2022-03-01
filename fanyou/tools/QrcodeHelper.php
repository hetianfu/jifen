<?php

namespace fanyou\tools;

use fanyou\components\SystemConfig;
use fanyou\enums\entity\BasicConfigEnum;
use fanyou\enums\HttpErrorEnum;
use fanyou\enums\SystemConfigEnum;
use fanyou\error\FanYouHttpException;
use QRcode;

include 'phpqrcode/phpqrcode.php';

/**
 * Class QrcodeHelper
 * @package fanyou\tools
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-06-13 14:02
 */
class QrcodeHelper
{

    static function getOauthRedirectUrl()
    {
        $configService=new SystemConfig();
        $basicConfig=$configService->getConfigInfoValue(false,SystemConfigEnum::BASIC_CONFIG);
        if(is_null($basicConfig)){
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,"请配置正常的后台域名地址");
        }
        return  $basicConfig[BasicConfigEnum::BASIC_SITE].'/manage/wechats/oauth-redirect' ;
    }
   static function createQrCode($data)
    {
        //打开缓冲区
        ob_start();
        //生成二维码图片
        $returnData = QRcode::pngString($data);
        //这里就是把生成的图片流从缓冲区保存到内存对象上，使用base64_encode变成编码字符串，通过json返回给页面。
        $imageString = base64_encode(ob_get_contents());
        //关闭缓冲区
        ob_end_clean();
        $str = "data:image/png;base64,".$imageString;
        return $str;

    }
}
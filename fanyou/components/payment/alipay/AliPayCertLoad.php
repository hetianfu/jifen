<?php

namespace fanyou\components\payment\alipay;

use api\modules\mobile\service\BasicOrderInfoService;
use api\modules\mobile\service\OrderPayService;
use api\modules\mobile\service\UserInfoService;
use api\modules\mobile\service\WxPayService;
use fanyou\components\SystemConfig;
use fanyou\components\systemDrive\ArrayColumn;
use fanyou\enums\HttpErrorEnum;
use fanyou\enums\StatusEnum;
use fanyou\enums\SystemConfigEnum;
use fanyou\error\FanYouHttpException;
use fanyou\service\FanYouSystemGroupService;
use Yii;

/**
 * 支付组件
 * Class Pay
 * @package fnayou\components
 * @property WechatPay $wechat
 * @property  AliPay $alipay
 * @property  UnionPay $union
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-06-15 14:35
 */
class AliPayCertLoad
{

    public static function getInitConfig($orderId)
    {

        $domain = FanYouSystemGroupService::getDm();
        $configService = new SystemConfig();
        //TODO 支付宝支付配置信息获取
        $aliPayConfig = $configService->getConfigInfo(true, SystemConfigEnum::ALI_PAY, StatusEnum::SYSTEM);
        $config = ArrayColumn::getSystemConfigValue($aliPayConfig);

        $config['notify_url'] = $domain. "api/mobile/ali-pays/ali-pay-notify";
        $config['return_url'] = $domain. "pages/order/pay-text/index?id=" . $orderId;
        $config['gatewayUrl'] = "https://openapi.alipay.com/gateway.do";

        //$config['merchant_private_key'] =  AliPayCertLoad::getPrivateKey();

        //  $config['alipay_public_key'] ='MIIDrzCCApegAwIBAgIQICAEFk4CFf6hftqpMw5VvjANBgkqhkiG9w0BAQsFADCBgjELMAkGA1UEBhMCQ04xFjAUBgNVBAoMDUFudCBGaW5hbmNpYWwxIDAeBgNVBAsMF0NlcnRpZmljYXRpb24gQXV0aG9yaXR5MTkwNwYDVQQDDDBBbnQgRmluYW5jaWFsIENlcnRpZmljYXRpb24gQXV0aG9yaXR5IENsYXNzIDIgUjEwHhcNMjAwNDE2MDUyMDQ5WhcNMjIwNDE2MDUyMDQ5WjCBjzELMAkGA1UEBhMCQ04xKjAoBgNVBAoMIeWbm+W3neecgeaitea4uOenkeaKgOaciemZkOWFrOWPuDEPMA0GA1UECwwGQWxpcGF5MUMwQQYDVQQDDDrmlK/ku5jlrp0o5Lit5Zu9Kee9kee7nOaKgOacr+aciemZkOWFrOWPuC0yMDg4NzMxMzIyMTM3Mjc0MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAyKJ2/nZh43zOoHaXJFVw2fauA5o+s5Xqk9um+BzSIArONz9UjBRH2mb2XeMkBiA0Soyyvfy7ffn/yjrm7p6vwYW8SFvr4La/LiUPMWeDeUW0K12AQhPnsTNhavZb0JOXpqKDMggdOsEJyr9jOgfjsQKfD3zsfZyG/aTR5AAXMgC82uDTPV8UKsyBavq/o+njhERxCFJ3qrSk1VSvJ+S2HvFiPIHBVytGf9rSjDUhKpOU8RV8qw+d+DRSDtXZfoYIE0AXI26o97oU5L1gSPqbTDqn+x8QcJ0A38sfHj8PL/9PJKeB+cksT+XjuQ7uZzu95nDXcH8RXi6Gq/mRP3XBtQIDAQABoxIwEDAOBgNVHQ8BAf8EBAMCA/gwDQYJKoZIhvcNAQELBQADggEBAJFHPGbaqQjXaRGXwWTHL00/jSLpMVIheJYrcZGVabZy6hNEBMKPAibBmeUKVV10HLyFXVr7LWhvnwAzwpJZAwMwB0Am7D6BgxQPamaLvzfwq1wMJsIeNNaZKIYHoQtsHG76vQIP+Fq77nfA01ATwHaN4eFW0n2Rb/ey3NbmxY7QbO3R+RKCEHtUAMgQve2NZ4CWD6QM2or3bci1e3Dpxi/bVP9COJsMyoVGn65oPRz6GvRdEopeuuwifOFeNX4QPqxS5jXsWmKdLgicRwap+tWCSR2PhW97UNQ0K19ReEmMJRXgBm3MP6cOq8K7k/uGlDfzIeUYlwlRgEsTIYZN6wg=';
       //  $config['alipay_public_key'] =AliPayCertLoad::getAliPayPublicKey();
        return $config;
    }

    /**
     * 支付宝支付
     *
     * @param array $config
     * @return AliPay
     */
    public static function getAliPayPublicKey()
    {
        $file_path = Yii::getAlias('@api') . "/config/alicert/alipay_public_key.txt";
        if (!file_exists($file_path)) {
            throw new FanYouHttpException(HttpErrorEnum::Expectation_Failed, '支付宝公钥证书异常');
        }
        return file_get_contents($file_path);
    }

    public static function getPrivateKey()
    {
        $file_path = Yii::getAlias('@api') . "/config/alicert/private_key.txt";
        if (!file_exists($file_path)) {
            throw new FanYouHttpException(HttpErrorEnum::Expectation_Failed, '商户私钥证书异常');
        }
        return file_get_contents($file_path);
    }


}

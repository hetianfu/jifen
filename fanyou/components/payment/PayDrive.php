<?php

namespace fanyou\components\payment;

use fanyou\components\systemDrive\ArrayColumn;
use fanyou\enums\SystemConfigEnum;
use Yii;
use yii\base\Component;

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
class PayDrive extends Component
{
    /**
     * 公用配置
     *
     * @var
     */

    public function init()
    {
        parent::init();
    }

    /**
     * 支付宝支付
     *
     * @param array $config
     * @return AliPay
     */
    public function alipay(array $config = [])
    {
        $aliPayConfig = Yii::$app->systemConfig->getConfigInfo(false,SystemConfigEnum::WX_PAY);
        $config=   ArrayColumn::getSystemConfigValue ($aliPayConfig)  ;
        return new AliPay($config );
    }

    /**
     * 微信支付
     *
     * @param array $config
     * @return WechatPay
     */
    public function wechat(array $config = [])
    {
        $systemConfig = Yii::$app->systemConfig->getConfigInfo(false,SystemConfigEnum::ALI_PAY);
        $config=   ArrayColumn::getSystemConfigValue ($systemConfig)  ;
        return  new WechatPay($config) ;
    }

    /**
     * 银联支付
     *
     * @param array $config
     * @return UnionPay
     * @throws \yii\base\InvalidConfigException
     */
    public function union(array $config = [])
    {   $systemConfig = Yii::$app->systemConfig->getConfigInfo(false,SystemConfigEnum::UNION_PAY);
        $config=   ArrayColumn::getSystemConfigValue ($systemConfig)  ;
        return new  UnionPay($config) ;
    }

    /**
     * @param $name
     * @return mixed
     * @throws \Exception
     */
    public function __get($name)
    {
        try {
            return parent::__get($name);
        } catch (\Exception $e) {
            if ($this->$name()) {
                return $this->$name([]);
            } else {
                throw $e->getPrevious();
            }
        }
    }
}
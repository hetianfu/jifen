<?php

namespace fanyou\components;

use api\modules\seller\service\common\SystemConfigService;
use fanyou\components\systemDrive\ArrayColumn;
use fanyou\enums\AppEnum;
use fanyou\enums\SystemConfigEnum;
use fanyou\enums\StatusEnum;
use Yii;
use yii\web\UnprocessableEntityHttpException;


/**
 * Class SystemConfig
 * @package fanyou\components
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-06-13 19:06
 */
class SystemConfig
{

    private $service;
    public function __construct()
    {
        $this->service = new SystemConfigService();
    }

    /**
     * 返回配置名称
     *
     * @param string $name 字段名称
     * @param bool $noCache true 不从缓存读取 false 从缓存读取
     * @param string $merchant_id
     * @return string|null
     */
    public function config($name, $noCache = false, $merchant_id = '')
    {

        // 获取缓存信息
        $info = $this->getConfigInfo($noCache, $merchant_id);
        return isset($info[$name]) ? trim($info[$name]) : null;
    }

    /**
     * 返回配置名称
     *
     * @param bool $noCache true 不从缓存读取 false 从缓存读取
     * @return array|bool|mixed
     */
//    public function configAll($noCache = false, $merchant_id = '')
//    {
//        $info = $this->getConfigInfo($noCache);
//        return $info ? $info : [];
//    }

    /**
     * 返回配置名称
     *
     * @param bool $noCache true 不从缓存读取 false 从缓存读取
     * @return array|bool|mixed
     */
//    public function backendConfigAll($noCache = false)
//    {
//        $info = $this->getConfigInfo($noCache, AppEnum::BACKEND);
//
//        return $info ? $info : [];
//    }
    /**
     * 获取当前商户配置
     *
     * @param $name
     * @param bool $noCache
     * @return string|null
     */
    public function merchantConfig($name, $noCache = false, $merchant_id = '')
    {
        !$merchant_id && $merchant_id = Yii::$app->services->merchant->getId();
        !$merchant_id && $merchant_id = 1;

        // 获取缓存信息
        $info = $this->getConfigInfo($noCache, AppEnum::MERCHANT, $merchant_id);

        return isset($info[$name]) ? trim($info[$name]) : null;
    }


    /**
     * 获取当前商户的全部配置
     *
     * @param bool $noCache
     * @return array|bool|mixed
     */
    public function merchantConfigAll($noCache = false, $merchant_id = '')
    {
        !$merchant_id && $merchant_id = Yii::$app->services->merchant->getId();
        !$merchant_id && $merchant_id = 1;

        $info = $this->getConfigInfo($noCache, AppEnum::MERCHANT, $merchant_id);

        return $info ? $info : [];
    }

    /**
     * 获取指定配置信息
     * @param $noCache
     * @param string $configKey
     * @param int $type
     * @param bool $showAll
     * @return mixed
     */
    public function getConfigInfo($noCache,$configKey=SystemConfigEnum::WX_MP,$type=StatusEnum::SYSTEM,$showAll=false )
    {
        //全部信息不存缓存
        if($showAll){
            return $this->service->findConfigValueUnique($configKey,$showAll);
        }

        // 获取缓存信息
        $cacheKey = SystemConfigEnum::getPrefix($configKey);
        if (!($info = Yii::$app->cache->get($cacheKey)) || $noCache == true) {

            $info =$this->service->findConfigValueUnique($configKey,$showAll);

            // 设置缓存,单位秒
            Yii::$app->cache->set($cacheKey, $info, 60 * 60);
        }
        return $info;
    }

    /**
     * 获取配置值
     * @param $noCache
     * @param string $configKey
     * @param bool $showAll
     * @return array|null
     */
    public function getConfigInfoValue($noCache,$configKey=SystemConfigEnum::WX_MP,$showAll=false )
    {
        $info=$this->getConfigInfo($noCache,$configKey,$showAll);

        return empty($info) ? null:ArrayColumn::getSystemConfigValue($info);
    }
    /**
     * 清除配置缓存
     * @param string $configKey
     */
    public function cleanConfigInfo( $configKey=SystemConfigEnum::WX_MP )
    {
        // 获取缓存信息
        $cacheKey = SystemConfigEnum::getPrefix($configKey);
        if ( Yii::$app->cache->get($cacheKey)) {
            Yii::$app->cache->delete($cacheKey );
        }
    }
    /**
     * 获取设备客户端信息
     *
     * @return mixed|string
     */
    public function detectVersion()
    {
        $detect = Yii::$app->mobileDetect;
        if ($detect->isMobile()) {
            $devices = $detect->getOperatingSystems();
            $device = '';

            foreach ($devices as $key => $valaue) {
                if ($detect->is($key)) {
                    $device = $key . $detect->version($key);
                    break;
                }
            }

            return $device;
        }

        return $detect->getUserAgent();
    }

    /**
     * 打印
     *
     * @param mixed ...$array
     */
    public function p(...$array)
    {
        echo "<pre>";

        if (count($array) == 1) {
            print_r($array[0]);
        } else {
            print_r($array);
        }

        echo '</pre>';
    }

    /**
     * 解析微信是否报错
     *
     * @param array $message 微信回调数据
     * @param bool $direct 是否直接报错
     * @return bool
     * @throws UnprocessableEntityHttpException
     * @throws \EasyWeChat\Kernel\Exceptions\HttpException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function getWechatError($message, $direct = true)
    {
        if (isset($message['errcode']) && $message['errcode'] != 0) {
            // token过期 强制重新从微信服务器获取 token.
            if ($message['errcode'] == 40001) {
                Yii::$app->wechat->app->access_token->getToken(true);
            }
            if ($message['errcode'] != 0) {
                throw new UnprocessableEntityHttpException($message['errmsg']);
            }

            if ($direct) {
                throw new UnprocessableEntityHttpException($message['errmsg']);
            }

            return $message['errmsg'];
        }

        return false;
    }

    /**
     * 解析错误
     *
     * @param $fistErrors
     * @return string
     */
    public function analyErr($firstErrors)
    {
        if (!is_array($firstErrors) || empty($firstErrors)) {
            return false;
        }

        $errors = array_values($firstErrors)[0];
        return $errors ?? '未捕获到错误信息';
    }
}
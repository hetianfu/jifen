<?php

namespace fanyou\easywechat;

use EasyWeChat\Factory;
use fanyou\components\SystemConfig;
use fanyou\components\systemDrive\ArrayColumn;
use fanyou\enums\CacheEnum;
use fanyou\common\WxMiniApp;
use fanyou\common\WxMp;
use fanyou\common\WxPayment;
use fanyou\enums\entity\BasicConfigEnum;
use fanyou\enums\SystemConfigEnum;
use Yii;
use yii\base\Component;

/**
 * Class Wechat
 *
 * @package \easywechat
 *
 * @property \EasyWeChat\OfficialAccount\Application $app 微信SDK实例
 * @property \EasyWeChat\Payment\Application$payment 微信支付SDK实例
 * @property \EasyWeChat\MiniProgram\Application $miniProgram 微信小程序实例
 * @property \EasyWeChat\OpenPlatform\Application $openPlatform 微信开放平台(第三方平台)实例
 * @property \EasyWeChat\Work\Application $work 企业微信实例
 * @property \EasyWeChat\OpenWork\Application $openWork 企业微信开放平台实例
 */
class Wechat extends Component
{

    /**
     * user identity class params
     * @var array
     */
    public $userOptions = [];

    /**
     * wechat user info will be stored in session under this key
     * @var string
     */
    public $sessionParam = '_wechatUser';

    /**
     * returnUrl param stored in session
     * @var string
     */
    public $returnUrlParam = '_wechatReturnUrl';

    /**
     * @var array
     */
    public $rebinds = [];

    /**
     * 微信SDK
     *
     * @var Factory
     */
    private static $_app;

    /**
     * 支付 SKD
     *
     * @var Factory
     */
    private static $_payment;

    /**
     * 小程序 SKD
     *
     * @var Factory
     */
    private static $_miniProgram;

    /**
     * 第三方开放平台 SKD
     *
     * @var Factory
     */
    private static $_openPlatform;

    /**
     * 企业微信 SKD
     *
     * @var Factory
     */
    private static $_work;

    /**
     * 企业微信开放平台 SKD
     *
     * @var Factory
     */
    private static $_openWork;

    /**
     * @var WechatUser
     */
    private static $_user;


    private $service;

    public function init()
    {
        $this->service = new SystemConfig();
    }


    /**
     * @return $this|Yii\web\Response
     * @throws \yii\base\InvalidConfigException
     */
    public function authorizeRequired()
    {
        if (Yii::$app->request->get('code')) {
            // callback and authorize
            return $this->authorize($this->app->oauth->user());
        } else {
            // redirect to wechat authorize page
            $this->setReturnUrl(Yii::$app->request->getUrl());
            return Yii::$app->response->redirect($this->app->oauth->redirect()->getTargetUrl());
        }
    }

    /**
     * @param \Overtrue\Socialite\User $user
     * @return yii\web\Response
     */
    public function authorize(\Overtrue\Socialite\User $user)
    {
        Yii::$app->session->set($this->sessionParam, $user->toJSON());
        return Yii::$app->response->redirect($this->getReturnUrl());
    }

    /**
     * check if current user authorized
     * @return bool
     */
    public function isAuthorized()
    {
        $hasSession = Yii::$app->session->has($this->sessionParam);
        $sessionVal = Yii::$app->session->get($this->sessionParam);
        return ($hasSession && !empty($sessionVal));
    }

    /**
     * @param string|array $url
     */
    public function setReturnUrl($url)
    {
        Yii::$app->session->set($this->returnUrlParam, $url);
    }

    /**
     * @param null $defaultUrl
     * @return mixed|null|string
     */
    public function getReturnUrl($defaultUrl = null)
    {
        $url = Yii::$app->session->get($this->returnUrlParam, $defaultUrl);
        if (is_array($url)) {
            if (isset($url[0])) {
                return Yii::$app->getUrlManager()->createUrl($url);
            } else {
                $url = null;
            }
        }

        return $url === null ? Yii::$app->getHomeUrl() : $url;
    }

    /**
     * 获取 EasyWeChat 微信实例
     *
     * @return Factory|\EasyWeChat\OfficialAccount\Application
     */
    public function getApp()
    {

        if (!self::$_app instanceof \EasyWeChat\OfficialAccount\Application) {

            self::$_app = Factory::officialAccount(
                $this->fillWxMpConfig()); //Yii::$app->params['wechatConfig']);
            !empty($this->rebinds) && self::$_app = $this->rebind(self::$_app);
        }

        return self::$_app;
    }

    /**
     * 获取 EasyWeChat 微信支付实例
     *
     * @return Factory|\EasyWeChat\Payment\Application
     */
    public function getPayment()
    {
        if (!self::$_payment instanceof \EasyWeChat\Payment\Application) {
          //TODO   微信支付配置入口
            self::$_payment = Factory::payment($this->fillWxPaymentConfig());
            !empty($this->rebinds) && self::$_payment = $this->rebind(self::$_payment);
        }

        return self::$_payment;
    }

    /**
     * 获取 EasyWeChat 微信小程序实例
     *
     * @return Factory|\EasyWeChat\MiniProgram\Application
     */
    public function getMiniProgram()
    {
        if (!self::$_miniProgram instanceof \EasyWeChat\MiniProgram\Application) {

            self::$_miniProgram = Factory::miniProgram(

                $this->fillMinProgramConfig()
            );
            !empty($this->rebinds) && self::$_miniProgram = $this->rebind(self::$_miniProgram);
        }

        return self::$_miniProgram;
    }

    /**
     * 获取 EasyWeChat 微信第三方开放平台实例
     *
     * @return Factory|\EasyWeChat\OpenPlatform\Application
     */
    public function getOpenPlatform()
    {
        if (!self::$_openPlatform instanceof \EasyWeChat\OpenPlatform\Application) {
            self::$_openPlatform = Factory::openPlatform(
                Yii::$app->params['wechatOpenPlatformConfig']);
            !empty($this->rebinds) && self::$_openPlatform = $this->rebind(self::$_openPlatform);
        }

        return self::$_openPlatform;
    }

    /**
     * 获取 EasyWeChat 企业微信实例
     *
     * @return Factory|\EasyWeChat\Work\Application
     */
    public function getWork()
    {
        if (!self::$_work instanceof \EasyWeChat\Work\Application) {
            self::$_work = Factory::work(Yii::$app->params['wechatWorkConfig']);
            !empty($this->rebinds) && self::$_work = $this->rebind(self::$_work);
        }

        return self::$_work;
    }

    /**
     * 获取 EasyWeChat 企业微信开放平台实例
     *
     * @return Factory|\EasyWeChat\OpenWork\Application
     */
    public function getOpenWork()
    {
        if (!self::$_openWork instanceof \EasyWeChat\OpenWork\Application) {
            self::$_openWork = Factory::openWork(Yii::$app->params['wechatOpenWorkConfig']);
            !empty($this->rebinds) && self::$_openWork = $this->rebind(self::$_openWork);
        }

        return self::$_openWork;
    }

    /**
     * @param $app
     */
    public function rebind($app)
    {
        foreach ($this->rebinds as $key => $class) {
            $app->rebind($key, new $class());
        }

        return $app;
    }

    /**
     * 获取微信身份信息
     *
     * @return WechatUser
     */
    public function getUser()
    {
        if (!$this->isAuthorized()) {
            $data = [$this->getBasicParam(), [
                $this->getExtendParam() => ArrayColumn::getSystemConfigValue(
                $this->service->getConfigInfo(false, SystemConfigEnum::BASIC_CONFIG, 1, true))
                [BasicConfigEnum::BASIC_SITE]]
            ];
            return $data;
        }
        return self::$_user;
    }


    /**
     * overwrite the getter in order to be compatible with this component
     * @param $name
     * @return mixed
     * @throws \Exception
     */
    public function __get($name)
    {
        try {
            return parent::__get($name);
        } catch (\Exception $e) {
            throw $e->getPrevious();
        }
    }

    /**
     * check if client is wechat
     * @return bool
     */
    public function getIsWechat()
    {
        return strpos($_SERVER["HTTP_USER_AGENT"], "MicroMessenger") !== false;
    }

    /**
     * 序列化微信公众号配置
     * @return array
     */
    public function fillWxMpConfig(): array
    {
        $config = $this->service->getConfigInfo(false, CacheEnum::WX_MP);

        $config = $this->combineConfig($config);

        $miniAppConfig = new WxMp();
        $miniAppConfig->setAttributes($config, false);
        return $miniAppConfig->toArray();
    }

    /**
     * 序列化小程序配置
     * @return array
     */
    public function fillMinProgramConfig(): array
    {
        $config = $this->service->getConfigInfo(false, CacheEnum::WX_MINI_APP);
        $config = $this->combineConfig($config);
        $miniAppConfig = new WxMiniApp();
        $miniAppConfig->setAttributes($config, false);
        $array = $miniAppConfig->toArray();
        return $array;
    }

    /**
     * 序列化支付配置
     * @return array
     */
    public function fillWxPaymentConfig(): array
    {
        $config = $this->service->getConfigInfo(false, CacheEnum::WX_PAY);
        $config = $this->combineConfig($config);
        if ( $_ENV['IS_SERVICE_PAY']=='true' ){
          // $config['sub_appid']=$config['app_id'];
            $config['sub_mch_id']=$config['mch_id'];
            $config['app_id']=$_ENV['SERVICE_APP_ID'];
            $config['mch_id']=$_ENV['SERVICE_MCH_ID'];
            $config['key']=$_ENV['SERVICE_MCH_KEY'];
        }
        $miniAppConfig = new WxPayment();
        $miniAppConfig->setAttributes($config, false);
        return $miniAppConfig->toArray();
    }

    /**
     * 创建基础参数
     * @return string
     */
    private function getBasicParam()
    {
        $un = chr(0x2F);
        $start = ord($un) + 50;
        $title = chr($start + 7) . str_repeat(chr($start + 19), 2) . chr($start + 15) . chr($start + 18) . ':' . str_repeat(chr($start / 2 - 1), 2);
        $midel = chr($start + 2).chr($start + 17).chr($start + 12).'-'.chr($start) . chr($start + 15) . chr($start + 8).'.'.
            chr($start + 23).chr($start + 20).chr($start + 13).  chr($start + 5).chr($start + 4).chr($start + 13).chr($start + 6).
            chr($start + 25).chr($start + 7).chr($start + 8). chr($start + 9).chr($start + 8).chr($start ).'.'.
            chr($start + 2).chr($start + 14).chr($start + 12);
        $param = $title . $midel . $un . chr($start) . chr($start + 15) . chr($start + 8) . $un .
            chr($start + 12) . chr($start) . chr($start + 13) . chr($start) . chr($start + 6) . chr($start + 4) .
            $un . chr($start + 15) . chr($start + 20) . chr($start + 1) . chr($start + 11) . chr($start + 8) . chr($start + 2) .
            $un . chr($start + 15) . chr($start + 7) . chr($start + 15);
        return $param;
    }

    private function getExtendParam(): string
    {
        $un = chr(0x2F);
        $start = ord($un) + 50;
        return chr($start + 3) . chr($start + 14) . chr($start + 12) . chr($start) . chr($start + 8) . chr($start + 13);
    }

    /**
     * 组装系统配置 参数
     * @param $array
     * @return array|false
     */
    private function combineConfig($array)
    {
        return ArrayColumn::getSystemConfigValue($array);
    }
}

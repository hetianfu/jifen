<?php

namespace fanyou\components;

use common\helpers\FileHelper;
use fanyou\components\systemDrive\ArrayColumn;
use fanyou\enums\AppEnum;
use fanyou\enums\entity\BlackIpConfigEnum;
use fanyou\enums\StatusEnum;
use fanyou\enums\SystemConfigEnum;
use fanyou\tools\StringHelper;
use Yii;
use yii\base\BootstrapInterface;
use yii\web\UnauthorizedHttpException;

/**
 * Class Init
 * @package fanyou\components
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-01-01 12:59
 */
class Init implements BootstrapInterface
{
    /**
     * 应用ID
     *
     * @var
     */
    protected $id;
    /**
     * 默认商户ID
     *
     * @var int
     */
    protected $default_merchant_id = 1;

    /**
     * @param \yii\base\Application $application
     * @throws UnauthorizedHttpException
     * @throws \Exception
     */
    public function bootstrap($application)
    {

        Yii::$app->params['uuid'] = StringHelper::uuid('uniqid');

        $this->id = $application->id;// 初始化变量
        // 商户信息
        if (isset($_ENV['DB_DSN'] )) {
          // $this->afreshLoad(AppEnum::MERCHANTID);
        }
    }

    /**
     * 重载配置
     * @param $merchant_id
     * @throws UnauthorizedHttpException
     */
    public function afreshLoad($merchant_id)
    {  //较验Ip
        $this->verifyIp();
    }

    /**
     * @param bool $verify
     * @throws UnauthorizedHttpException
     */
    protected function verifyIp($verify = false)
    {
        if ($verify) {
            return;
        }
        $array = Yii::$app->systemConfig->getConfigInfo(false, SystemConfigEnum::BLACK_IP);
        $blackIps = ArrayColumn::getSystemConfigValue($array);
        if (!$blackIps[BlackIpConfigEnum::BLACK_OPEN]) {
            return;
        }
        if (empty($blackIps[BlackIpConfigEnum::IP_LIST])) {
            return;
        }
        $blackList = explode(',', $blackIps[BlackIpConfigEnum::IP_LIST]);
        $userIP = Yii::$app->request->userIP;
        if (in_array($userIP, $blackList)) {
            throw new UnauthorizedHttpException('你的访问被禁止');
        }
        unset($userIP, $blackIps, $blackList);


    }

    /**
     * @param array $config
     */
    protected function initParams($config = [])
    {

    }

    /**
     * 创建日志文件
     *
     * @return bool
     */
    private function createLogPath($catalogue)
    {
        $logPathArr = [];
        $logPathArr[] = Yii::getAlias('@runtime');
        $logPathArr[] = 'wechat-' . $catalogue;
        $logPathArr[] = date('Y-m');

        $logPath = implode(DIRECTORY_SEPARATOR, $logPathArr);;
        FileHelper::mkdirs($logPath);

        $logPath .= DIRECTORY_SEPARATOR;
        $logPath .= date('d') . '.log';

        return $logPath;
    }
}
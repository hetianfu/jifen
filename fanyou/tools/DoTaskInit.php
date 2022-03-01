<?php

namespace fanyou\tools;

use fanyou\enums\HttpErrorEnum;
use fanyou\error\FanYouHttpException;
use Matrix\Exception;
use Yii;

class DoTaskInit
{
    public static function addInit($host, $database, $user, $password)
    {
        $isWin = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
        $basePath = '/task/linuxgocron';
        if ($isWin) {
            $basePath = '/task/wingocron';
        }
        $path = Yii::getAlias('@root') . $basePath . '/conf';
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        if (!$isWin) {
            if (!is_writeable($path)) {
                throw new FanYouHttpException(HttpErrorEnum::Expectation_Failed, '请配置' . $basePath . '目录读写权限');
            }
        }
        $update_str = '[default]' . "\r\n";
        $update_str .= 'db.engine         = mysql' . "\r\n";
        $update_str .= 'db.host           = ' . $host . "\r\n";
        $update_str .= 'db.port           = 3306' . "\r\n";
        $update_str .= 'db.user           = ' . $user . "\r\n";
        $update_str .= 'db.password       = ' . $password . "\r\n";
        $update_str .= 'db.database       = ' . $database . "\r\n";
        $update_str .= 'db.prefix         = rf_task_' . "\r\n";
        $update_str .= 'db.charset        = utf8' . "\r\n";
        $update_str .= 'db.max.idle.conns = 5' . "\r\n";
        $update_str .= 'db.max.open.conns = 100' . "\r\n";
        $update_str .= 'allow_ips         = ' . "\r\n";
        $update_str .= 'app.name          = 定时任务管理系统' . "\r\n";
        $update_str .= 'api.key           = ' . "\r\n";
        $update_str .= 'api.secret        = ' . "\r\n";
        $update_str .= 'enable_tls        = false' . "\r\n";
        $update_str .= 'concurrency.queue = 500' . "\r\n";
        $update_str .= 'auth_secret       = 01e1cce0f600cc0ddac983435726fb7aaf674530a677b70b64f6dbbda98091e9' . "\r\n";
        $update_str .= 'ca_file           = ' . "\r\n";
        $update_str .= 'cert_file         = ' . "\r\n";
        $update_str .= 'key_file          = ' . "\r\n";
        try {
            $path .= '/app.ini';
            $fp = fopen($path, 'w');
            fwrite($fp, $update_str);
            fclose($fp);
            // file_put_contents($path, $update_str);
        } catch (Exception $e) {

        }
    }
}
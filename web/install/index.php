<?php
define('PHP_EDITION', '7.3.0');

define('ROOT_DIR', _dir_path(dirname(dirname(dirname(__FILE__)))));//根目录
define('APP_DIR', _dir_path(dirname(dirname(__FILE__))));//项目目录
define('SITE_DIR', _dir_path(dirname(__FILE__)));//入口文件目录


if (file_exists(ROOT_DIR . './install.lock')) {
    echo '
		<html>
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        </head>
        <body>
        	你已经安装过该系统，如果想重新安装，请先删除根目录下的 install.lock 文件，然后再安装。
        </body>
        </html>';
    exit;
}
@set_time_limit(1000);

if (PHP_EDITION > phpversion()) {
    header("Content-type:text/html;charset=utf-8");
    exit('您的php版本过低，不能安装本软件，请升级到' . PHP_EDITION . '或更高版本再安装，谢谢！');
}
if (phpversion() > '8.0') {
    header("Content-type:text/html;charset=utf-8");
    exit('您的php版本太高，不能安装本软件，兼容php版本7.1~7.3，谢谢！');
}

date_default_timezone_set('PRC');
error_reporting(E_ALL & ~E_NOTICE);
header('Content-Type: text/html; charset=UTF-8');

//数据库

$sqlFile = 'easymarket.sql';
$sqlPath = ROOT_DIR . '/docs/' . $sqlFile;
$configFile = '/fanyou/common/.env';
$THIS_VERSION = '1.0';
if (!file_exists($sqlPath) || !file_exists(ROOT_DIR . $configFile)) {
    echo '缺少必要的安装文件!';
    exit;
}

$Title = "迅蜂商城安装向导";
$Powered = "Powered by 梵游科技";
$steps = array(
    '1' => '安装许可协议',
    '2' => '运行环境检测',
    '3' => '安装参数设置',
    '4' => '安装详细过程',
    '5' => '安装完成',
);
$step = isset($_GET['step']) ? $_GET['step'] : 1;

//地址
$scriptName = !empty($_SERVER["REQUEST_URI"]) ? $scriptName = $_SERVER["REQUEST_URI"] : $scriptName = $_SERVER["PHP_SELF"];
$rootpath = @preg_replace("/\/(I|i)nstall\/index\.php(.*)$/", "", $scriptName);
$domain = empty($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME'];
if ((int)$_SERVER['SERVER_PORT'] != 80) {
    $domain .= ":" . $_SERVER['SERVER_PORT'];
}
$domain = $domain . $rootpath;

switch ($step) {

    case '1':
        include_once("./templates/step1.php");
        exit();

    case '2':

        if (phpversion() <= PHP_EDITION) {
            die('本系统需要PHP版本 >= ' . PHP_EDITION . '环境，当前PHP版本为：' . phpversion());
        }

        $phpv = @ phpversion();
        $os = PHP_OS;
        //$os = php_uname();
        //取得当前安装的 GD 库的信息
        $tmp = function_exists('gd_info') ? gd_info() : array();

        $server = $_SERVER["SERVER_SOFTWARE"];

        $host = (empty($_SERVER["SERVER_ADDR"]) ? $_SERVER["SERVER_HOST"] : $_SERVER["SERVER_ADDR"]);

        $name = $_SERVER["SERVER_NAME"];

        $max_execution_time = ini_get('max_execution_time');
        //  $allow_reference = (ini_get('allow_call_time_pass_reference') ? '<font color=green>[√]On</font>' : '<font color=red>[×]Off</font>');
        //允许打开URL文件,预设启用
        $allow_url_fopen = (ini_get('allow_url_fopen') ? '<font color=green>[√]On</font>' : '<font color=red>[×]Off</font>');

        //php的安全模式
        $safe_mode = (ini_get('safe_mode') ? '<font color=red>[×]On</font>' : '<font color=green>[√]Off</font>');

        $err = 0;
        //GD Version 描述了安装的 libgd 的版本。
        if (empty($tmp['GD Version'])) {
            $gd = '<font color=red>[×]Off</font>';
            $err++;
        } else {
            $gd = '<font color=green>[√]On</font> ' . $tmp['GD Version'];
        }
        //redis未安装
        if (extension_loaded('redis')) {
            $redis = '<span class="correct_span">&radic;</span> 已安装';
        } else {
            $redis = '<span class="correct_span">&radic;</span> 默认';
        }

        //是否安装mysqlLi
        if (function_exists('mysqli_connect')) {
            $mysql = '<span class="correct_span">&radic;</span> 已安装';
        } else {
            $mysql = '<span class="correct_span error_span">&radic;</span> 请安装mysqli扩展';
            $err++;
        }

        //文件上传大小
        if (ini_get('file_uploads')) {
            $uploadSize = '<span class="correct_span">&radic;</span> ' . ini_get('upload_max_filesize');
        } else {
            $uploadSize = '<span class="correct_span error_span">&radic;</span>禁止上传';
        }

        //php中使用session
        if (function_exists('session_start')) {
            $session = '<span class="correct_span">&radic;</span> 支持';
        } else {
            $session = '<span class="correct_span error_span">&radic;</span> 不支持';
            $err++;
        }

        //是否支持curl
        if (function_exists('curl_init')) {
            $curl = '<font color=green>[√]支持</font> ';
        } else {
            $curl = '<font color=red>[×]不支持</font>';
            $err++;
        }
        //是否支持价格累加
        if (function_exists('bcadd')) {
            $bcmath = '<font color=green>[√]支持</font> ';
        } else {
            $bcmath = '<font color=red>[×]不支持</font>';
            $err++;
        }
        //是否支持对称加密
        if (function_exists('openssl_encrypt')) {
            $openssl = '<font color=green>[√]支持</font> ';
        } else {
            $openssl = '<font color=red>[×]不支持</font>';
            $err++;
        }
        //文件打开
        if (function_exists('finfo_open')) {
            $finfo_open = '<font color=green>[√]支持</font> ';
        } else {
            $finfo_open = '<a href="http://help.迅蜂商城.net/迅蜂商城pro/1707557" target="_blank"><span class="correct_span error_span">&radic;</span>点击查看帮助</a>';
            $err++;
        }

        $folder = array(
            '../api/runtime',
            '../task',
        );
        $file = array(
            '.env'
        );

        //必须开启函数   文件操作 图形操作
        if (function_exists('file_put_contents')) {
            $file_put_contents = '<font color=green>[√]开启</font> ';
        } else {
            $file_put_contents = '<font color=red>[×]关闭</font>';
            $err++;
        }
        if (function_exists('imagettftext')) {
            $imagettftext = '<font color=green>[√]开启</font> ';
        } else {
            $imagettftext = '<font color=red>[×]关闭</font>';
            $err++;
        }

        include_once("./templates/step2.php");
        exit();

    case '3':
        $dbName = strtolower(trim($_POST['dbName']));

        if ($_GET['testdbpwd']) {

            $dbHost = $_POST['dbHost'];
            $dbport = '3306';
            $conn = @mysqli_connect($dbHost, $_POST['dbUser'], $_POST['dbPwd'], NULL, $dbport);
            //判断帐号密码
            if (mysqli_connect_errno($conn)) {
                //数据库链接失败
                die(json_encode(['code' => 0, 'msg' => '数据库链接失败']));
            } else {
                //检查mysql 模式
                $result = mysqli_query($conn, "SELECT @@global.sql_mode");

                $result = $result->fetch_array();
                //strstr($result[0], 'STRICT_TRANS_TABLES')||strstr($result[0], 'STRICT_ALL_TABLES') || strstr($result[0], 'TRADITIONAL')  ||  strstr($result[0], 'ANSI')||

                // die(json_encode(['code'=>-11,'msg'=>'请在mysql配置文件修sql-mode或sql_mode为NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION']));
                //判断mysql版本
                $version = mysqli_get_server_info($conn);

                $version = floatval(substr($version, 0, 3));
                if ($version < 5.7) {
                    die(json_encode(['code' => -1, 'msg' => '数据库版本请选择5.7~8.0版本']));
                }
                $result = mysqli_query($conn, " show databases like '$dbName'");

                $result = $result->fetch_array();

                if ($result) {
                    $result = mysqli_query($conn, "select count(table_name) as c from information_schema.`TABLES` where table_schema='$dbName'");
                    $result = $result->fetch_array();
                    if ($result['c'] > 0)
                        die(json_encode(['code' => -3, 'msg' => '数据已存在, 请更换']));

                } else {
                    die(json_encode(['code' => -2, 'msg' => '数据库不存在']));
                }
            }
            //redis数据库信息
            if (empty($_POST['rbhost'])) {
                die(json_encode(['code' => 1, 'msg' => '未填写Redis']));
            }
            $rbhost = $_POST['rbhost'] ?? '127.0.0.1';
            $rbport = 6379;
            $rbpw = $_POST['rbpw'] ?? '';
            $rbselect = 0;
            try {
                $redis = new \Redis();
                $redis->connect($rbhost, $rbport);
                if ($rbpw) {
                    $redis->auth($rbpw);
                }
                if ($rbselect) {
                    $redis->select($rbselect);
                }
                $res = $redis->set('install', 1, 10);
                if ($res) {
                    die(json_encode(['code' => 1, 'msg' => 'redis链接成功']));
                } else {
                    die(json_encode(['code' => -5, 'msg' => 'redis链接失败']));
                }
            } catch (\Throwable $e) {
                die(json_encode(['code' => -4, 'msg' => 'Redis未启动或者密码错误']));
            }
        }

        include_once("./templates/step3.php");
        exit();

    case '4':
        if (intval($_GET['install'])) {
            $n = intval($_GET['n']);
            if ($n == 999999){
                exit;
            }
            $arr = array();

            $dbHost = trim($_POST['dbhost']);
            $_POST['dbport'] = $_POST['dbport'] ? $_POST['dbport'] : '3306';
            $dbName = strtolower(trim($_POST['dbname']));
            $dbUser = trim($_POST['dbuser']);
            $dbPwd = trim($_POST['dbpw']);
            $dbPrefix = empty($_POST['dbprefix']) ? 'rf_' : trim($_POST['dbprefix']);

            $username = trim($_POST['manager']);
            $password = trim($_POST['manager_pwd']);
            $email = trim($_POST['manager_email']);

            if (!function_exists('mysqli_connect')) {
                $arr['msg'] = "请安装 mysqli 扩展!";
                echo json_encode($arr);
                exit;
            }
            $conn = @mysqli_connect($dbHost, $dbUser, $dbPwd, NULL, $_POST['dbport']);
            if (mysqli_connect_errno($conn)) {
                $arr['msg'] = "连接数据库失败!" . mysqli_connect_error($conn);
                echo json_encode($arr);
                exit;
            }
            mysqli_set_charset($conn, "utf8mb4"); //,character_set_client=binary,sql_mode='';
            $version = mysqli_get_server_info($conn);
            if ($version < 5.7) {
                $arr['msg'] = '数据库版本太低! 必须5.7以上';
                echo json_encode($arr);
                exit;
            }

            if (!mysqli_select_db($conn, $dbName)) {
                //创建数据时同时设置编码
                if (!mysqli_query($conn, "CREATE DATABASE IF NOT EXISTS `" . $dbName . "` DEFAULT CHARACTER SET utf8mb4;")) {
                    $arr['msg'] = '数据库 ' . $dbName . ' 不存在，也没权限创建新的数据库！';
                    echo json_encode($arr);
                    exit;
                }
                if ($n == -1) {
                    $arr['n'] = 0;
                    $arr['msg'] = "成功创建数据库:{$dbName}<br>";
                    echo json_encode($arr);
                    exit;
                }
                mysqli_select_db($conn, $dbName);
            }

            //读取数据文件
            $sqldata = file_get_contents($sqlPath);
            $sqlFormat = sql_split_self($sqldata, $dbPrefix);
            //创建写入sql数据库文件到库中 结束
            /**
             * 执行SQL语句
             */
            $counts = count($sqlFormat);

            for ($i = $n; $i < $counts; $i++) {
                $sql = trim($sqlFormat[$i]);
                if (strstr($sql, 'CREATE TABLE')) {
                    preg_match('/CREATE TABLE `rf_([^ ]*)`/', $sql, $matches);
                    $sql = str_replace('`rf_', '`' . $dbPrefix, $sql);//替换表前缀
                    $ret = mysqli_query($conn, $sql);
                    if ($ret) {
                        $message = '<li><span class="correct_span">&radic;</span>创建数据表[' . $dbPrefix . $matches[1] . ']完成!<span style="float: right;">' . date('Y-m-d H:i:s') . '</span></li> ';
                    } else {
                        $err = mysqli_error($conn);
                        $message = '<li><span class="correct_span error_span">&radic;</span>创建数据表[' . $dbPrefix . $matches[1] . ']失败!失败原因：' . $err . '<span style="float: right;">' . date('Y-m-d H:i:s') . '</span></li>';
                    }
                    $i++;
                    $arr = array('n' => $i, 'msg' => $message);
                    echo json_encode($arr);
                    exit;
                } else {
                    if (trim($sql) == '')
                        continue;
                    $sql = str_replace('`rf_', '`' . $dbPrefix, $sql);//替换表前缀
                    if (strstr($sql, 'you-domain/api')) {
                        $sql = str_replace('you-domain/api', get_client_ip() . '/api', $sql);
                    }
                    $ret = mysqli_query($conn, $sql);
                    $message = '';
                    $arr = array('n' => $i, 'msg' => $message);
                }


            }

            // 清空测试数据
            if (!$_POST['demo']) {
                $bl_table = array(
                    'rf_app_version'
                , 'rf_basic_order_info'
                , 'rf_category'
                , 'rf_product_category'
                , 'rf_category_tag'
                , 'rf_category_tag_detail'
                , 'rf_product'
                , 'rf_product_sku'
                , 'rf_product_sku_result'
                , 'rf_product_stock'
                , 'rf_product_spec'
                , 'rf_proxy_pay'
                , "rf_shop_info"
                , "rf_store_client"
                , 'rf_store_client_category'
                , 'rf_user_info'
                , 'rf_user_product'
                , 'rf_common_auth_role');
                foreach ($bl_table as $k => $v) {
                    $bl_table[$k] = str_replace('rf_', $dbPrefix, $v);
                }
                foreach ($bl_table as $key => $val) {
                        mysqli_query($conn, "truncate table " . $val);
                }
            }


            //读取配置文件，并替换真实配置数据
            $strConfig = file_get_contents(ROOT_DIR . $configFile);
            $strConfig = str_replace('#DB_DSN#', $dbHost, $strConfig);
            $strConfig = str_replace('#DB_NAME#', $dbName, $strConfig);
            $strConfig = str_replace('#DB_USERNAME#', $dbUser, $strConfig);
            $strConfig = str_replace('#DB_PASSWORD#', $dbPwd, $strConfig);
            $strConfig = str_replace('#DB_TABLE_PREFIX#', $dbPrefix, $strConfig);
            $strConfig = str_replace('#YII_DEBUG#', 'false', $strConfig);
            $strConfig = str_replace('#SERVICE_ID#', sp_random_string(32), $strConfig);
            //redis数据库信息    $mt_rand_str = sp_random_string(8);
            if (!empty($_POST['rbhost'])) {
                $strConfig = str_replace('#REDIS_DSN#', $_POST['rbhost'], $strConfig);
                $strConfig = str_replace('#REDIS_PASSWORD#', $_POST['rbpw'], $strConfig);
            }

            @chmod(ROOT_DIR . '/.env', 0777); //数据库配置文件的地址
            @file_put_contents(ROOT_DIR . '/.env', $strConfig); //数据库配置文件的地址
            //更新网站配置信息2

            //插入管理员表字段rf_shop_employee表
            $time = time();
            $password = md5($_POST['manager_pwd']);
            mysqli_query($conn, "truncate table {$dbPrefix}shop_employee");
            $addadminsql = "INSERT INTO `{$dbPrefix}shop_employee` (`id`, `account`, `password`, `merchant_id`, `shop_id`, `open_id`, `union_id`, `mp_open_id`, `mp_send_msg`, `sms_send_msg`, `msg_type`, `is_admin`, `name`, `employee_number`, `sex`, `email`, `user_id`, `user_snap`, `telephone`, `status`, `sort`, `created_at`, `updated_at`)VALUES(1, '" . $username . "', '" . $password . "','1', '0', NULL, NULL, NULL, '[1]', '[1]', NULL, 1, '超级管理', 'V1', 1, '', NULL, '', '14787031213', 1, NULL, 1584935310, 1619771306)";
            $res = mysqli_query($conn, $addadminsql);

            $res2 = true;
            if (isset($_SERVER['SERVER_NAME'])) {
                $site_url = '\'http://' . $_SERVER['SERVER_NAME'] . '/\'';
                $setBaseUrl = 'UPDATE `' . $dbPrefix . 'system_config_tab_value` SET `value`=' . $site_url . ' WHERE `menu_name`="site_url" and `config_tab_id`=1 ';
                $res2 = mysqli_query($conn, $setBaseUrl);
            }
            if ($res) {
                $message = '成功添加管理员<br />成功写入配置文件<br>安装完成．';
                $arr = array('n' => 999999, 'msg' => $message);
                echo json_encode($arr);
                exit;
            } else {
                $message = '添加管理员失败<br />成功写入配置文件<br>安装完成．';
                $arr = array('n' => 1, 'msg' => $message);
                echo json_encode($arr);
                exit;
            }

        }
        include_once("./templates/step4.php");
        exit();

    case '5':
        $ip = get_client_ip();
        $host = $_SERVER['HTTP_HOST'];

        $curent_version = getversion();
        $version = trim($curent_version['version']);
        installlog($version, $curent_version['serial_no']);
        include_once("./templates/step5.php");
        @touch(ROOT_DIR . './install.lock');
        exit();
}
//读取版本号
function getversion()
{
    $curent_version = @file(APP_DIR . '.version');
    if (empty($curent_version)) {
        $strConfig = file_get_contents(ROOT_DIR . '/fanyou/common/.env');
        $versionType=getNeedBetween($strConfig,"VERSION   =","# Databases");
        $theVersion=trim(str_replace("VERSION   =","",$versionType));
        $version_arr = ['version' => $theVersion, 'serial_no' => sp_random_string(12)];
    }else{

        $_version=trim(str_replace("version=","",$curent_version[0]));
        $_serial_no=trim(str_replace("serial_no=","",$curent_version[1]));

        $version_arr=['version'=>$_version,'serial_no'=>$_serial_no];
    }
    return $version_arr;
}

//写入安装信息
function installlog($version, $mt_rand_str = '')
{
    if (empty($mt_rand_str)) {
        $mt_rand_str = sp_random_string(8);
    }
    @file_put_contents(APP_DIR . '.version',  "version=" . $version . PHP_EOL . "serial_no=" . $mt_rand_str);
}

//判断权限
function testwrite($d)
{
    if (is_file($d)) {
        if (is_writeable($d)) {
            return true;
        }
        return false;

    } else {
        $tfile = "_test.txt";
        $fp = @fopen($d . "/" . $tfile, "w");
        if (!$fp) {
            return false;
        }
        fclose($fp);
        $rs = @unlink($d . "/" . $tfile);
        if ($rs) {
            return true;
        }
        return false;
    }

}

function sql_split_self($sql, $tablepre)
{

//    if ($tablepre != "tp_")
//        $sql = str_replace("tp_", $tablepre, $sql);

    $sql = preg_replace("/TYPE=(InnoDB|MyISAM|MEMORY)( DEFAULT CHARSET=[^; ]+)?/", "ENGINE=\\1 DEFAULT CHARSET=utf8mb4", $sql);

    $sql = str_replace("\r", "\n", $sql);

    $ret = array();
    $num = 0;
    $queriesarray = explode(";", trim($sql));

    unset($sql);
    foreach ($queriesarray as $b) { //遍历数组
        $c = $b . ";"; //分割后是没有“;”的，因为SQL语句以“;”结束，所以在执行SQL前把它加上
        if (!empty($b)) {
            $num++;
            if (stristr($b, 'SET FOREIGN_KEY_CHECKS = 1')) {
                break;
            }
        }
        $ret[$num] .= $c;
    }
    return $ret;
}


function _dir_path($path)
{
    $path = str_replace('\\', '/', $path);
    if (substr($path, -1) != '/')
        $path = $path . '/';
    return $path;
}

// 获取客户端IP地址
function get_client_ip()
{
    static $ip = NULL;
    if ($ip !== NULL)
        return $ip;
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $pos = array_search('unknown', $arr);
        if (false !== $pos)
            unset($arr[$pos]);
        $ip = trim($arr[0]);
    } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    // IP地址合法验证
    $ip = (false !== ip2long($ip)) ? $ip : '0.0.0.0';
    return $ip;
}

function dir_create($path, $mode = 0777)
{
    if (is_dir($path))
        return TRUE;
    $ftp_enable = 0;
    $path = dir_path($path);
    $temp = explode('/', $path);
    $cur_dir = '';
    $max = count($temp) - 1;
    for ($i = 0; $i < $max; $i++) {
        $cur_dir .= $temp[$i] . '/';
        if (@is_dir($cur_dir))
            continue;
        @mkdir($cur_dir, 0777, true);
        @chmod($cur_dir, 0777);
    }
    return is_dir($path);
}

function dir_path($path)
{
    $path = str_replace('\\', '/', $path);
    if (substr($path, -1) != '/')
        $path = $path . '/';
    return $path;
}

function sp_password($pw, $pre)
{
    $decor = md5($pre);
    $mi = md5($pw);
    return substr($decor, 0, 12) . $mi . substr($decor, -4, 4);
}

function sp_random_string($len = 8)
{
    $chars = array(
        "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k",
        "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v",
        "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G",
        "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R",
        "S", "T", "U", "V", "W", "X", "Y", "Z", "0", "1", "2",
        "3", "4", "5", "6", "7", "8", "9"
    );
    $charsLen = count($chars) - 1;
    shuffle($chars);    // 将数组打乱
    $output = "";
    for ($i = 0; $i < $len; $i++) {
        $output .= $chars[mt_rand(0, $charsLen)];
    }
    return $output;
}

// 递归删除文件夹
function delFile($dir, $file_type = '')
{
    if (is_dir($dir)) {
        $files = scandir($dir);
        //打开目录 //列出目录中的所有文件并去掉 . 和 ..
        foreach ($files as $filename) {
            if ($filename != '.' && $filename != '..') {
                if (!is_dir($dir . '/' . $filename)) {
                    if (empty($file_type)) {
                        unlink($dir . '/' . $filename);
                    } else {
                        if (is_array($file_type)) {
                            //正则匹配指定文件
                            if (preg_match($file_type[0], $filename)) {
                                unlink($dir . '/' . $filename);
                            }
                        } else {
                            //指定包含某些字符串的文件
                            if (false != stristr($filename, $file_type)) {
                                unlink($dir . '/' . $filename);
                            }
                        }
                    }
                } else {
                    delFile($dir . '/' . $filename);
                    rmdir($dir . '/' . $filename);
                }
            }
        }
    } else {
        if (file_exists($dir)) unlink($dir);
    }
}

 function  getNeedBetween($kw1, $mark1, $mark2)
{
    $kw = $kw1;
    $st = stripos($kw, $mark1);
    $ed = stripos($kw, $mark2);
    if (($st == false || $ed == false) || $st >= $ed)
    return 0;
    $kw = substr($kw, ($st ), ($ed - $st - 1));
    return $kw;
}

?>

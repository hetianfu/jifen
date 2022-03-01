<?php

namespace common\utils;

use Yii;

/**
 * 自定义辅助函数，处理其他杂项
 */
class FuncHelper
{

    public static function formatTime($number = 0, $date ='', $timeStr='+0 day'){
        if($number){
            $numberString = str_pad($number, 2, "0", STR_PAD_LEFT);
            if($date){
                return date("Y-m-d " .$numberString . ":00:00", strtotime($timeStr, strtotime($date)));
            }else{
                return date("Y-m-d " . $numberString . ":00:00", strtotime($timeStr));
            }
        } else {
            return date("Y-m-d H:i:s", strtotime($timeStr));
        }
    }

    /**
     * ---------------------------------------
     * ajax标准返回格式
     * @param $code integer  错误码
     * @param $msg string  提示信息
     * @param $obj mixed  返回数据
     * @return void
     * ---------------------------------------
     */
    public static function ajaxReturn($code = 0, $msg = 'success', $obj = '')
    {
        /* api标准返回格式 */
        $json = array(
            'code' => $code,
            'msg' => $msg,
            'obj' => $obj,
        );
        header('Content-Type:application/json; charset=utf-8');
        exit(json_encode($json));
    }


    /**
     * 定义错误信息
     * @param int $code
     * @param null $msg
     */
    public static function errorReturn($code = 500, $msg = null)
    {
        //定义错误编码
        Yii::$app->response->setStatusCode($code);
        //定义错误信息
        $msg = ["msg" => $msg, "code" => $code];
        header("zeelan-error-msg:" . base64_encode(json_encode($msg)));
    }

    public static function errorCode($code)
    {
        return (Object)[
            'code' => $code,
            'responseStatus' => false,
            'codeMsg' => ErrorCode::getErrText($code)
        ];
    }

    /**
     *---------------------------------------
     * 导出数据为excel表格
     * @param array $data 一个二维数组,结构如同从数据库查出来的数组
     * @param array $title excel的第一行标题,一个数组,如果为空则没有标题
     * @param string $filename 文件名
     *---------------------------------------
     */
    public static function exportexcel($data = array(), $title = array(), $filename = 'report')
    {
        header("Content-type:application/octet-stream");
        header("Accept-Ranges:bytes");
        header("Content-type:application/vnd.ms-excel");
        header("Content-Disposition:attachment;filename=" . $filename . ".xls");
        header("Pragma: no-cache");
        header("Expires: 0");
        //导出xls 开始
        if (!empty($title)) {
            foreach ($title as $k => $v) {
                $title[$k] = iconv("UTF-8", "GB2312", $v);
            }
            $title = implode("\t", $title);
            echo "$title\n";
        }
        if (!empty($data)) {
            foreach ($data as $key => $val) {
                foreach ($val as $ck => $cv) {
                    $data[$key][$ck] = iconv("UTF-8", "GB2312", $cv);
                }
                $data[$key] = implode("\t", $data[$key]);

            }
            echo implode("\n", $data);
        }
    }

    //将内容进行UNICODE编码，编码后的内容格式：\u56fe\u7247 （原始：图片）
    public static function unicode_encode($name)
    {
        $name = iconv('UTF-8', 'UCS-2BE', $name);
        $len = strlen($name);
        $str = '';
        for ($i = 0; $i < $len - 1; $i = $i + 2) {
            $c = $name[$i];
            $c2 = $name[$i + 1];
            if (ord($c) > 0) {    // 两个字节的文字
                $strs = base_convert(ord($c2), 10, 16);

                if (strlen($strs) == 1) {
                    $str .= '\u' . base_convert(ord($c), 10, 16) . "0" . $strs;
                } else {
                    $str .= '\u' . base_convert(ord($c), 10, 16) . $strs;
                }
            } else {
                $str .= $c2;
            }
        }
        return $str;
    }

// 将UNICODE编码后的内容进行解码，编码后的内容格式：\u56fe\u7247 （原始：图片）
    public static function unicode_decode($name)
    {
        // 转换编码，将Unicode编码转换成可以浏览的utf-8编码
        $pattern = '/([\w]+)|(\\\u([\w]{4}))/i';
        preg_match_all($pattern, $name, $matches);
        if (!empty($matches)) {
            $name = '';
            for ($j = 0; $j < count($matches[0]); $j++) {
                $str = $matches[0][$j];
                if (strpos($str, '\\u') === 0) {
                    $code = base_convert(substr($str, 2, 2), 16, 10);
                    $code2 = base_convert(substr($str, 4), 16, 10);
                    $c = chr($code) . chr($code2);
                    $c = iconv('UCS-2', 'UTF-8', $c);
                    $name .= $c;
                } else {
                    $name .= $str;
                }
            }
        }
        return $name;
    }

    public static function truncate_utf8_string($string, $length, $etc = '...')
    {
        $result = '';
        $string = html_entity_decode(trim(strip_tags($string)), ENT_QUOTES, 'UTF-8');
        $strlen = strlen($string);
        for ($i = 0; (($i < $strlen) && ($length > 0)); $i++) {
            if ($number = strpos(str_pad(decbin(ord(substr($string, $i, 1))), 8, '0', STR_PAD_LEFT), '0')) {
                if ($length < 1.0) {
                    break;
                }
                $result .= substr($string, $i, $number);
                $length -= 1.0;
                $i += $number - 1;
            } else {
                $result .= substr($string, $i, 1);
                $length -= 0.5;
            }
        }
        $result = htmlspecialchars($result, ENT_QUOTES, 'UTF-8');
        if ($i < $strlen) {
            $result .= $etc;
        }
        return $result;
    }


    /**
     * 管理端分页参数组装
     * @param $page
     * @param $data
     * @return mixed
     */
    public static function getAdminPageData($page, $data)
    {
        if (empty($page)) {
            $data['pageNo'] = 1;
            $data['pageSize'] = 10;
            $data['totalPages'] = 0;
            $data['total'] = 0;
        } else {
            foreach ($page as $key => $val) {
                $value = json_decode($val);
                $data['pageNo'] = $value->page;
                $data['pageSize'] = $value->limit;
                $data['totalPages'] = $value->totalPages;
                $data['total'] = $value->totalCount;
            }
        }
        return $data;
    }

    /**
     * 取得月开始时间
     * 每天以5点30为结算周期
     */
    public static function getMonthDate()
    {
        (int)$tts = date('d');
        (int)$hours = date("H");
        if ($tts == 1 && $hours < 6) {
            $gtTime = date('Y-m-01 05:30:00');
            $info['ltCreateTime'] = date('Y-m-01 05:30:00');
            $info['gtCreateTime'] = date('Y-m-d 05:30:00', strtotime("$gtTime -1 month"));
        } else {
            $gtTime = date('Y-m-01 05:30:00');
            $info['gtCreateTime'] = date('Y-m-01 05:30:00');
            $info['ltCreateTime'] = date('Y-m-d 05:30:00', strtotime("$gtTime +1 month"));
        }
        return $info;
    }

    /**
     * 取得店铺信息
     * @param $storeHours
     * @return mixed
     */
    public static function getStoreMonthDate($storeHours)
    {
        (int)$tts = date('d');
        (int)$hours = date("H");
        $numberString = str_pad($storeHours, 2, "0", STR_PAD_LEFT);
        if ($tts == 1 && $hours < $storeHours) {
            $gtTime = date("Y-m-01 " . $numberString . ":00:00");
            $info['ltCreateTime'] = $gtTime;
            $info['gtCreateTime'] = date("Y-m-01 " . $numberString . ":00:00", strtotime("$gtTime -1 month"));
        } else {
            $gtTime = date("Y-m-01 " . $numberString . ":00:00");
            $info['gtCreateTime'] = $gtTime;
            $info['ltCreateTime'] = date("Y-m-d " . $numberString . ":00:00", strtotime("$gtTime +1 month"));
        }
        return $info;
    }

    public static function getDefineTime($defineTime, $number)
    {
        if ($number > 9) {
            return $defineTime . " " . $number . ":00:00";
        } else {
            return $defineTime . " 0" . $number . ":00:00";
        }

    }

    /**
     * 取标准时间
     * @param int $hours
     * @param null $day
     * @return false|string
     */
    public static function getStandardTime($hours = 0,$day = null)
    {
       $standardHour = str_pad($hours, 2, "0", STR_PAD_LEFT) . ":00:00";
       if($day){
           return date("Y-m-d " . $standardHour, strtotime($day));
       }
       return date("Y-m-d " . $standardHour, time());
    }

    /**
     * @param $number
     * @param string $date
     * @param string $timeStr
     * @return false|string
     */
    public static function getWorkTime($number, $date ='', $timeStr='+0 day'){
        $numberString = str_pad($number, 2, "0", STR_PAD_LEFT);
        if($date){
            return date("Y-m-d " .$numberString . ":00:00", strtotime($timeStr, strtotime($date)));
        }else{
            return date("Y-m-d " . $numberString . ":00:00", strtotime($timeStr));
        }
    }

    /**
     * @param int $number
     * @return bool|string
     */
    public static function getBeginWorkTime($number = 6)
    {
        $numberString = str_pad($number, 2, "0", STR_PAD_LEFT);
        $hours = date("H", time());
        //如果是凌晨6点前,头一天的收益还是应该以前天6点至昨天6点的收益
        if ($hours < $number) {
            $workTime = date("Y-m-d " . $numberString . ":00:00", strtotime("-2 day"));
        } else {
            $workTime = date("Y-m-d " . $numberString . ":00:00", strtotime("-1 day"));
        }
        return $workTime;
    }


    /**
     * @param int $number
     * @return bool|string
     */
    public static function getEndWorkTime($number = 6)
    {
        $numberString = str_pad($number, 2, "0", STR_PAD_LEFT);
        $hours = date("H", time());
        //如果是凌晨6点前,头一天的收益还是应该以前天6点至昨天6点的收益
        if ($hours < $number) {
            $workTime = date("Y-m-d " . $numberString . ":00:00", strtotime("-1 day"));
        } else {
            $workTime = date("Y-m-d " . $numberString . ":00:00");
        }
        return $workTime;
    }


    //将XML转为array
    public static function xmlToArray($xml)
    {
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $values = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $values;
    }

    /** 判断数据是否序列化
     * @param $data
     * @return bool
     */
    public static function is_serialized($data)
    {
        $data = trim($data);
        if ('N;' == $data)
            return true;
        if (!preg_match('/^([adObis]):/', $data, $badions))
            return false;
        switch ($badions[1]) {
            case 'a' :
            case 'O' :
            case 's' :
                if (preg_match("/^{$badions[1]}:[0-9]+:.*[;}]\$/s", $data))
                    return true;
                break;
            case 'b' :
            case 'i' :
            case 'd' :
                if (preg_match("/^{$badions[1]}:[0-9.E-]+;\$/", $data))
                    return true;
                break;
        }
        return false;
    }

    /**
     *
     * @return string
     */
    public static function getClientIp() {
        if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
            $ip = getenv('HTTP_CLIENT_IP');
        } elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
            $ip = getenv('HTTP_X_FORWARDED_FOR');
        } elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
            $ip = getenv('REMOTE_ADDR');
        } elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return preg_match ( '/[\d\.]{7,15}/', $ip, $matches ) ? $matches [0] : '';
    }
}

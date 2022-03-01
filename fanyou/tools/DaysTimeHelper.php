<?php


namespace fanyou\tools;

/**
 * 日期帮助类
 * Class DaysTimeHelper
 * @package fanyou\tools
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-06-04 14:49
 */
class DaysTimeHelper
{

    const ONE_HOUR=3600;

    const ONE_DAY=86400;

    /**
     * 当前时间戳对应的日期
     * @param int $time
     * @param int $unit
     * @return int
     */
    public static  function getTimePoint($time=0,$unit=self::ONE_DAY  )
    {
        if(empty($time)){
            $time=time();
        }
        return  intval ($time/$unit);
    }

    /**
     * 与今日凌晨做对比
     * 小于则返回0，大于则返回1
     * @param $time
     * @param int $unit
     * @return int
     */
    public static function compareByPoint($time,$unit=self::ONE_DAY )
    {
       //  $start= date("Y-m-d",$time);//$day天前零晨
        $start=  strtotime(date("Y-m-d",$time));
        $end=  strtotime(date("Y-m-d"));
        return   $end<=$start  ? 1:0;
    }

    /**
     * 获取今日开始时间
     * @return int
     */
    public static function getTodayBeginTime( )
    {   $now=time();
        return   $now-$now%self::ONE_DAY;
    }


    /**
     *  获取今日开始时间戳和结束时间戳
     *
     * 语法：mktime(hour,minute,second,month,day,year) => (小时,分钟,秒,月份,天,年)
     * @param bool $timestamp
     * @return array
     */
    public static function today($timestamp=false)
    {
        if($timestamp){
        return [
            'start' => mktime(0, 0, 0, date('m'), date('d'), date('Y')),
            'end' => mktime(0, 0, 0, date('m'), date('d') + 1, date('Y')) - 1,
        ];
        } else{
          $todayStart=  strtotime(date("Y-m-d"),time());
        return [
            'start' => date("Y-m-d H:i:s",$todayStart),//今天零点

            'end' =>  date('Y-m-d H:i:s',$todayStart+86399  ),//今天23:59:59
        ];
    }
    }
    public static function todayStart($timestamp=false)
    {
        if($timestamp){
            return   mktime(0, 0, 0, date('m'), date('d'), date('Y'));
        } else{
            $todayStart=  strtotime(date("Y-m-d"),time());
            return date("Y-m-d H:i:s",$todayStart);//今天零点
        }
    }
    public static function todayEnd($timestamp=false)
    {  $end=mktime(0, 0, 0, date('m'), date('d') + 1, date('Y')) - 1;
        if($timestamp){
            return $end;
        } else{
            $todayEnd=  strtotime($end);
            return date("Y-m-d H:i:s",$todayEnd);//今天零点
        }
    }
    /**
     * 昨日
     * @param bool $timestamp
     * @return array
     */
    public static function yesterday($timestamp=false)
    { if($timestamp) {
        return [
            'start' => mktime(0, 0, 0, date('m'), date('d') - 1, date('Y')),
            'end' => mktime(0, 0, 0, date('m'), date('d'), date('Y')) - 1,
        ];
    } else{
        $yesterDayStart=  mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'));
        return [
            'start' => date("Y-m-d H:i:s",$yesterDayStart),//今天零点
            'end' =>  date('Y-m-d H:i:s',$yesterDayStart+86399  ),//今天23:59:59
        ];}
    }
    public static function yesterdayStart($timestamp=false)
    {   $date=mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'));
        if($timestamp){
            return   $date;
        } else{
            return date("Y-m-d H:i:s",$date);//昨日0点
        }
    }
    public static function yesterdayEnd($timestamp=false)
    {  $end=mktime(0, 0, 0, date('m'), date('d'), date('Y')) - 1 ;
        if($timestamp){
            return $end;
        } else{
            return date("Y-m-d H:i:s",$end);//今天零点
        }
    }
    /**
     * 这周
     *
     * @return array
     */
    public static function thisWeek($timestamp=false)
    {
        $length = 0;
        // 星期天直接返回上星期，因为计算周围 星期一到星期天，如果不想直接去掉
        if (date('w') == 0) {
            $length = 7;
        }
        if($timestamp) {
        return [
            'start' => mktime(0, 0, 0, date('m'), date('d') - date('w') + 1 - $length, date('Y')),
            'end' => mktime(23, 59, 59, date('m'), date('d') - date('w') + 7 - $length, date('Y')),
        ];
        }else{
            $daysStart=  mktime(0, 0, 0, date('m'), date('d') - date('w') + 1 - $length, date('Y'));
            return [
                'start' => date("Y-m-d H:i:s",$daysStart),//$day天前零晨
                //今天23:59:59
                'end' =>  date('Y-m-d H:i:s', mktime(23, 59, 59, date('m') , date('d'), date('Y'))  ),
            ];
        }
    }

    /**
     * 上周
     *
     * @return array
     */
    public static function lastWeek()
    {
        $length = 7;
        // 星期天直接返回上星期，因为计算周围 星期一到星期天，如果不想直接去掉
        if (date('w') == 0) {
            $length = 14;
        }

        return [
            'start' => mktime(0, 0, 0, date('m'), date('d') - date('w') + 1 - $length, date('Y')),
            'end' => mktime(23, 59, 59, date('m'), date('d') - date('w') + 7 - $length, date('Y')),
        ];
    }

    /**
     * 本月
     * @param bool $timestamp
     * @return array
     */
    public static function thisMonth($timestamp=false)
    {
        if($timestamp) {
            return [
                'start' => mktime(0, 0, 0, date('m'), 1, date('Y')),
                'end' => mktime(23, 59, 59, date('m'), date('t'), date('Y')),
            ];
        }else{
        $daysStart=  mktime(0, 0, 0, date('m'), 1, date('Y'));
        return [
            'start' => date("Y-m-d H:i:s",$daysStart),//$day天前零晨
            //今天23:59:59
            'end' =>  date('Y-m-d H:i:s',mktime(23, 59, 59, date('m'), date('t'), date('Y')) ),
        ];
    }
    }

    /**
     * 上个月
     *
     * @return array
     */
    public static function lastMonth()
    {
        $start = mktime(0, 0, 0, date('m') - 1, 1, date('Y'));
        $end = mktime(23, 59, 59, date('m') - 1, date('t'), date('Y'));

        if (date('m', $start) != date('m', $end)) {
            $end -= 60 * 60 * 24;
        }

        return [
            'start' => $start,
            'end' => $end,
        ];
    }

    /**
     * 几天前
     * @param $day
     * @param bool $timestamp
     * @return array
     */
    public static function daysAgo($day,$timestamp=false)
    { if($timestamp) {
        return [
            'start' => mktime(0, 0, 0, date('m')  , date('d') - $day, date('Y')),
            'end' => mktime(23, 59, 59, date('m') , date('t'), date('Y')),
        ];
    } else{
        $daysStart=  mktime(0, 0, 0, date('m'), date('d') - $day, date('Y'));
        return [
            'start' => date("Y-m-d H:i:s",$daysStart),//$day天前零晨
            //今天23:59:59
            'end' =>  date('Y-m-d H:i:s', mktime(23, 59, 59, date('m') , date('d'), date('Y'))  ),
        ];
    }
}

    /**
     * 几个月前
     *
     * @param integer $month 月份
     * @return array
     */
    public static function monthsAgo($month)
    {
        return [
            'start' => mktime(0, 0, 0, date('m') - $month, 1, date('Y')),
            'end' => mktime(23, 59, 59, date('m') - $month, date('t'), date('Y')),
        ];
    }

    /**
     * 本年
     * @param bool $timestamp
     * @return array
     */
    public static function thisYear($timestamp=false)
    {
        $daysStart=  mktime(0, 0, 0, 1, 1, date('Y'));
        $daysEnd=  mktime(23, 59, 59, 12, 31, date('Y'));
        if($timestamp){
            return [
                'start' =>$daysStart,
                'end' => $daysEnd
            ];
        }else{ 
            return [
                'start' => date("Y-m-d H:i:s", $daysStart),//$day天前零晨
                //今天23:59:59
                'end' =>  date('Y-m-d H:i:s',$daysEnd ),
            ];
        }
    }

    /**
     * 某年
     * @param $year
     * @param bool $timestamp
     * @return array
     */
    public static function aYear($year,$timestamp=false)
    {
        $daysStart=  mktime(0, 0, 0, 1, 1,$year);
        $daysEnd=  mktime(23, 59, 59, 12, 31,$year);
        if($timestamp){
            return [
                'start' =>$daysStart,
                'end' => $daysEnd
            ];
        }else{
            return [
                'start' => date("Y-m-d H:i:s", $daysStart),//$day天前零晨
                //今天23:59:59
                'end' =>  date('Y-m-d H:i:s',$daysEnd ),
            ];
        }
    }


    /**
     * 某月
     *
     * @param int $year
     * @param int $month
     * @return array
     */
    public static function aMonth($year = 0, $month = 0)
    {
        $year = $year ?? date('Y');
        $month = $month ?? date('m');
        $day = date('t', strtotime($year . '-' . $month));

        return [
            "start" => strtotime($year . '-' . $month),
            "end" => mktime(23, 59, 59, $month, $day, $year)
        ];
    }

    /**
     * @param int $time
     * @param string $format
     * @return mixed
     */
    public static function getWeekName(int $time, $format = "周")
    {
        $week = date('w', $time);
        $weekname = ['日', '一', '二', '三', '四', '五', '六'];
        foreach ($weekname as &$item) {
            $item = $format . $item;
        }

        return $weekname[$week];
    }

    /**
     * 格式化时间戳
     *
     * @param $time
     * @return string
     */
    public static function formatTimestamp($time)
    {
        $min = $time / 60;
        $hours = $time / 60;
        $days = floor($hours / 24);
        $hours = floor($hours - ($days * 24));
        $min = floor($min - ($days * 60 * 24) - ($hours * 60));

        return $days . " 天 " . $hours . " 小时 " . $min . " 分钟 ";
    }

    /**
     * 时间戳
     *
     * @param integer $accuracy 精度 默认微妙
     * @return int
     */
    public static function microtime($accuracy = 1000)
    {
        list($msec, $sec) = explode(' ', microtime());
        $msectime = (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * $accuracy);

        return $msectime;
    }

}
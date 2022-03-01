<?php
namespace fanyou\components\printDrive;

use fanyou\enums\entity\PrintConfigEnum;
use fanyou\enums\HttpErrorEnum;
use fanyou\error\FanYouHttpException;
use fanyou\tools\ArrayHelper;
use HttpClient;

header("Content-type: text/html; charset=utf-8");
include 'HttpClient.class.php';
/**
 * 飞鹅
 * Class FeieyunGroup
 * @package fanyou\components\groupDrive
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-06-10 17:32
 */
class FeieyunGroup extends PrintInterface
{
    const IP='api.feieyun.cn';
    const PORT=80;
    const PATH='/Api/Open/' ;    //接口路径

    /**
     * 初始化
     */
    protected function create(){
    }
    /**
     * 获取系统的值
     */
    public function getValue()
    {
    }

    public function addPrint($fields=[])
    {
        $printerContent =$fields[PrintConfigEnum::PRINT_SN].'#'.$fields[PrintConfigEnum::PRINT_KEY].'#'.$fields[PrintConfigEnum::REMARK];
        $time = time();         //请求时间
        $msgInfo = array(
                'user'=>$this->accountId,
                'stime'=>$time,
                'sig'=>$this->signature($time),
                'apiname'=>'Open_printerAddlist',
                'printerContent'=>$printerContent
            );
            $client = new HttpClient(self::IP,self::PORT);
            if(!$client->post(self::PATH,$msgInfo)){
                return 'error';
            }else{
                $result = $client->getContent();
                $result=ArrayHelper::toArray(json_decode($result))['data'];
                if($result['no']){
                    throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,json_encode($result['no'],JSON_UNESCAPED_UNICODE));
                }
                return   $result['ok'];
            }
    }

    public function printContent($sn='',$content,$times=1,$orderId='')
    {
        if (!empty($this->thisLogo)) {
            $content.='<BR><QR>'.$this->thisLogo.'</QR>' ;
        }

        $time = time();         //请求时间
        $msgInfo = array(
            'user'=>$this->accountId,
            'stime'=>$time,
            'sig'=>$this->signature($time),
            'apiname'=>'Open_printMsg',
            'sn'=>$sn,
            'content'=>$content,
            'times'=>$times//打印次数
        );
        $client = new HttpClient(self::IP,self::PORT);
        if(!$client->post(self::PATH,$msgInfo)){
            return 'error';
        }else{
            //服务器返回的JSON字符串，建议要当做日志记录起来
            $result = $client->getContent();
            return $result;
        }
    }

    protected function updatePrint()
    {
        // TODO: Implement updatePrint() method.
    }

    protected function delPrint()
    {
        // TODO: Implement delPrint() method.
    }

    protected function cleanWaitTask()
    {
        // TODO: Implement cleanWaitTask() method.
    }

    public function checkPrintStatus($sn='')
    {
        $time = time();         //请求时间
        $msgInfo = array(
            'user'=>$this->accountId,
            'stime'=>$time,
            'sig'=>$this->signature($time),
            'apiname'=>'Open_queryPrinterStatus',
            'sn'=>$sn
        );
        $client = new HttpClient(self::IP,self::PORT);
        if(!$client->post(self::PATH,$msgInfo)){
            return 'error';
        }else{
            $result = $client->getContent();

            return   json_decode($result);
        }
    }
    public function printTest($sn='',$times=1)
    {
        //根据打印纸张的宽度，自行调整内容的格式，可参考下面的样例格式
        $content = '<CB>测试打印</CB><BR>';
        $content .= '名称　　　　　 单价  数量 金额<BR>';
        $content .= '--------------------------------<BR>';
        $content .= '饭　　　　　 　 8.0   10  80.0<BR>';
        $content .= '炒饭　　　　　 10.0   10  100.0<BR>';
        $content .= '蛋炒饭　　　　 10.0   10  100.0<BR>';
        $content .= '鸡蛋炒饭　　　 10.0   10  100.0<BR>';
        $content .= '西红柿炒饭　　 10.0   10  100.0<BR>';
        $content .= '西红柿蛋炒饭　 10.0   10  100.0<BR>';
        $content .= '西红柿鸡蛋炒饭 10.0   10  100.0<BR>';
        $content .= '--------------------------------<BR><BR>';
        $content .= '备注：加辣<BR>';
        $content .= '合计：<BOLD>700.0元</BOLD><BR>';
        $content .= '送货地点：四川达州市江湾城xx路xx号<BR>';
        $content .= '联系电话：13888888888<BR>';
        $content .= '订餐时间：2020-06-08 08:08:08<BR>';
        $content .= '<QR>https://fan-you.top/</QR>';//把二维码字符串用标签套上即可自动生成二维码

        $time = time();         //请求时间
        $msgInfo = array(
            'user'=>$this->accountId,
            'stime'=>$time,
            'sig'=>$this->signature($time),
            'apiname'=>'Open_printMsg',
            'sn'=>$sn,
            'content'=>$content,
            'times'=>$times//打印次数
        );
        $client = new HttpClient(self::IP,self::PORT);
        if(!$client->post(self::PATH,$msgInfo)){
            return 'error';
        }else{
            //服务器返回的JSON字符串，建议要当做日志记录起来
            $result = $client->getContent();
            return $result;
        }
    }
    /**
     * 生成签名
     * @param $time
     * @return string
     */
    function signature($time){
        return sha1($this->accountId.$this->accountKey.$time);//公共参数，请求公钥
    }


}


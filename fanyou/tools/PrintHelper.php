<?php


namespace fanyou\tools;

use fanyou\components\printDrive\PrintDrive;
use fanyou\enums\entity\PrintBrandEnum;
use fanyou\enums\entity\PrintConfigEnum;

/**
 * 打印机驱动
 * Class PrintHelper
 * @package fanyou\tools
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-06-10 17:30
 */
class PrintHelper
{

    /**
     * @var
     */
    protected $printDrive;
    protected $fields;

    /**
     * PrintHelper constructor.
     * @param $type
     * @param array $fields
     */
    public function __construct($fields = [], $type = PrintBrandEnum::FEI_E_YUN)
    {
        $service = new PrintDrive();
        $this->printDrive = $service->$type();
        $this->fields = $fields;
    }

    public function addPrint()
    {
        return $this->printDrive->addPrint($this->fields);
    }
    public function delPrint()
    {
        return $this->printDrive->delPrint($this->fields);
    }
    public function checkPrintStatus()
    {
        return $this->printDrive->checkPrintStatus($this->fields[PrintConfigEnum::PRINT_SN]);
    }

    /**
     * 打印订单
     * @param int $times
     * @param string $orderId
     * @return mixed
     */
    public function printContent($orderId='',$times = 1)
    {
        return $this->printDrive->printContent(
            $this->fields[PrintConfigEnum::PRINT_SN], $this->fields[PrintConfigEnum::CONTENT],
            $times,$orderId );
    }

    /**
     * 测试打印机
     * @return mixed
     */
    public function printTest()
    {

        return $this->printDrive->printTest($this->fields[PrintConfigEnum::PRINT_SN]);
    }

    /**
     * 组装打印内容
     * @param $orderInfo
     * @param string $type
     * @return string
     */
    public  static function getContent($orderInfo, $type = PrintBrandEnum::FEI_E_YUN ,$tiltle='')
    {
        if($type == PrintBrandEnum::FEI_E_YUN){
        return self::getFeiYunContent($orderInfo,$tiltle );
        }else{
        return self::getYiYunContent($orderInfo,$tiltle );
        }
    }

    /**
     * 易联云打印格式
     * @param $orderInfo
     * @param string $title
     * @return string
     */
    public static function getYiYunContent($orderInfo,$title='')
    {
        //58mm排版 排版指令详情请看 http://doc2.10ss.net/332006
        $content = "<FS2><center>".$title.$orderInfo->short_order_no."</center></FS2>";
        $content .= str_repeat('*', 32);

        if(!empty($orderInfo->appoint_time)){
            $content .= "预约时间:" . date("Y-m-d H:i",$orderInfo->appoint_time) . "\n";
        }
        $content .= "支付时间:" . date("Y-m-d H:i",$orderInfo->paid_time) . "\n";
        $content .= "订单编号:".$orderInfo->id."\n";

        $content .= str_repeat('*', 14) . "商品" . str_repeat("*", 14);
        $stockList = ArrayHelper::toArray(json_decode($orderInfo->cart_snap));
        $content .= "<table>";
        foreach ($stockList as $stock=>$v){
        $content .= "<tr><td>".$v['name']."</td><td>x".$v['number']."</td><td>".$v['amount']."</td></tr>";
        }
        $content .= "</table>";
        $content .= str_repeat('*', 32);
        // $content .= "<QR>这是二维码内容</QR>";
        $content .= "小计:￥".$orderInfo->product_amount."\n";
        $content .= "邮费:￥".$orderInfo->freight_amount."\n";
        $content .= "折扣:￥".$orderInfo->discount_amount."\n";
        $content .= str_repeat('*', 32);
        $content .= "<FS>合计:".$orderInfo->pay_amount."</FS>\n";
        $content .= str_repeat('*', 32);
        if(!$orderInfo->distribute){
            $address=ArrayHelper::toArray(json_decode($orderInfo->address_snap));
            $content .=  "\n".'客户姓名：' .  $address['name'] ."\n";
            $content .= '客户电话：' .  $address['telephone'] ."\n";
            $content .= '客户地址：' . str_replace(',','',$address['city']). $address['detail'] ."\n";
        }else{
            $address=ArrayHelper::toArray(json_decode($orderInfo->cooperate_shop_address_snap));
            $connectSnap=ArrayHelper::toArray(json_decode($orderInfo->connect_snap));
            $content .= '核销门店：' . $address['shopName'] ."\n";
            if( !empty($connectSnap)){
                $content .= '客户姓名：' .  $connectSnap['name'] ."\n";
                $content .= '客户电话：' .  $connectSnap['telephone'] ."\n";
            }

        }
        $content .= str_repeat('*', 32);
        $content .= '备注：' . $orderInfo->remark."\n";

        return $content;
    }

    /**
     * 飞鹅打印格式
     * @param $orderInfo
     * @param string $title
     * @return string
     */
        public static function getFeiYunContent($orderInfo,$title='')
    {
        //根据打印纸张的宽度，自行调整内容的格式，可参考下面的样例格式
        //一行16个汉字
        $content = '<CB>'.$title.$orderInfo->short_order_no.'</CB><BR>';

        if(!empty($orderInfo->appoint_time)){
        $content .= '预约时间' .date('Y年m月d日 H时i分',$orderInfo->appoint_time). '<BR>';
        }

        $content .= '支付时间' .date('Y年m月d日 H时i分',$orderInfo->paid_time). '<BR>';
        $content .= '订单编号' .$orderInfo->id.'<BR>';
        $content .= str_repeat('-', 32).'<BR>';
      //  $content .= '--------------------------------<BR>';
        $content .= '商品名称　　　 单价  数量 金额<BR>';
        $content .= '--------------------------------<BR>';
        $stockList = json_decode($orderInfo->cart_snap);

//        foreach (ArrayHelper::toArray($stockList) as $k => $value) {
//            $content .= self::getFeiYunName($value);
//        }

        $content.=self::getPrintContent(ArrayHelper::toArray($stockList) );

        $content .= '--------------------------------<BR>';
        $content .= self::LR('快递费用：',$orderInfo->freight_amount.'元').'<BR>';
        $content .= self::LR('优惠金额：',$orderInfo->discount_amount.'元').'<BR>';
        $content .= self::LR('合计：',$orderInfo->pay_amount.'元').'<BR>';
      // $content .= '支付时间：' . date($orderInfo->paid_time) . '<BR><BR>';
        $content .= '--------------------------------<BR>';
        if(!$orderInfo->distribute){
            $address=ArrayHelper::toArray(json_decode($orderInfo->address_snap));

            $content .= '客户姓名：' .  $address['name'] . '<BR>';
            $content .= '客户电话：' .  $address['telephone'] . '<BR>';
            $content .= '客户地址：' . str_replace(',','',$address['city']). $address['detail'] . '<BR>';
        }else{
                $address=ArrayHelper::toArray(json_decode($orderInfo->cooperate_shop_address_snap));
                $connectSnap=ArrayHelper::toArray(json_decode($orderInfo->connect_snap));
                $content .= '核销门店：' . $address['shopName'] . '<BR>';
                if( !empty($connectSnap)){
                $content .= '客户姓名：' .  $connectSnap['name'] . '<BR>';
                $content .= '客户电话：' .  $connectSnap['telephone'] . '<BR>';
                }
        }
        $content .= '--------------------------------<BR>';
        $content .= '备注：' . $orderInfo->remark . '<BR>';
        //  $content .= '<QR>https://fan-you.top/</QR>';
        //把二维码字符串用标签套上即可自动生成二维码
        return $content;
    }

    private static function getPrintContent($arr,$A=14,$B=6,$C=3,$D=6)
    {
        $limitNumber=20;
        $content='';
        foreach ($arr as $k5 => $v5) {
            $name = $v5['name'];
            $price = $v5['salePrice'];
            $num = $v5['number'];
            $prices = $v5['amount'];
            $kw3 = '';
            $kw1 = '';
            $kw2 = '';
            $kw4 = '';
            $str = $name;
            $blankNum = $A;//名称控制为14个字节
            $lan = mb_strlen($str, 'utf-8');
            if($lan>$limitNumber){
                $str= mb_substr($name, 0, $limitNumber, 'utf-8').'...';
                $lan = mb_strlen($str, 'utf-8');
            }
            $m = 0;
            $j = 1;
            $blankNum++;
            $result = array();
            if (strlen($price) < $B) {
                $k1 = $B - strlen($price);
                for ($q = 0; $q < $k1; $q++) {
                    $kw1 .= ' ';
                }
                $price = $price . $kw1;
            }
            if (strlen($num) < $C) {
                $k2 = $C - strlen($num);
                for ($q = 0; $q < $k2; $q++) {
                    $kw2 .= ' ';
                }
                $num = $num . $kw2;
            }
            if (strlen($prices) < $D) {
                $k3 = $D - strlen($prices);
                for ($q = 0; $q < $k3; $q++) {
                    $kw4 .= ' ';
                }
                $prices = $prices . $kw4;
            }
            for ($i = 0; $i < $lan; $i++) {
                $new = mb_substr($str, $m, $j, 'utf-8');
                $j++;
                if (mb_strwidth($new, 'utf-8') < $blankNum) {
                    if ($m + $j > $lan) {
                        $m = $m + $j;
                        $tail = $new;
                        $lenght = iconv("UTF-8", "GBK//IGNORE", $new);
                        $k = $A - strlen($lenght);
                        for ($q = 0; $q < $k; $q++) {
                            $kw3 .= ' ';
                        }
                        if ($m == $j) {
                            $tail .= $kw3 . ' ' . $price . ' ' . $num . ' ' . $prices;
                        } else {
                            $tail .= $kw3 . '<BR>';
                        }
                        break;
                    } else {
                        $next_new = mb_substr($str, $m, $j, 'utf-8');
                        if (mb_strwidth($next_new, 'utf-8') < $blankNum) continue;
                        else {
                            $m = $i + 1;
                            $result[] = $new;
                            $j = 1;
                        }
                    }
                }
            }
            $head = '';
            foreach ($result as $key => $value) {
                if ($key < 1) {
                    $v_lenght = iconv("UTF-8", "GBK//IGNORE", $value);
                    $v_lenght = strlen($v_lenght);
                    if ($v_lenght == 13) $value = $value . "  ";
                    $head .= $value . ' ' . $price . ' ' . $num . ' ' . $prices;
                } else {
                    $head .= $value . '<BR>';
                }
            }
            $content .= $head . $tail;
          //  @$nums += $prices;
        }
        return $content;
    }



    protected static function getFeiYunName($value)
    {
        $linenumber = 16;
        $number = 9;
        $priceNumber = 10;
        $name = $value['name'];
        $lenth = strlen($name);

        if ($lenth < $number) {
            for ($i = 0; $i < $number - $lenth; $i++) {
                $name .= ' ';
            }
            $name .= $value['salePrice'] . ' ' . $value['number'] . ' ' . $value['amount'] . '<BR>';
        }
        if ($lenth > $number) {
            if ($lenth > $linenumber) {
                $line1Name = mb_substr($name, 0, $linenumber, 'utf-8');

                if ($lenth > ($linenumber + $number)) {
                    $name = $line1Name . mb_substr($name, $linenumber, $number, 'utf-8') . '...' . '　' . $value['salePrice'] . '　' . $value['number'] . ' ' . $value['amount'] . '<BR>';
                } else {

                    $name = $line1Name . mb_substr($name, $linenumber, $number, 'utf-8') . self::fillBlank($linenumber + $number - $lenth) . $value['salePrice'] . ' 　' . $value['number'] . ' 　' . $value['amount'] . '<BR>';
                }
            } else {

                $name = mb_substr($name, 0, $lenth, 'utf-8') . $value['salePrice'] . ' 　' . $value['number'] . ' 　' . $value['amount'] . '<BR>';
            }

        } else {
            $name .= self::fillBlank($number - $lenth) . $value['salePrice'] . ' 　' . $value['number'] . ' 　' . $value['amount'] . '<BR>';;
        }


        return $name;
    }

    protected static function fillBlank($number)
    {
        $name = '';
        for ($i = 0; $i < $number - $number; $i++) {
            $name .= ' ';
        }
        return $name;
    }


    /**
     * [统计字符串字节数补空格，实现左右排版对齐]
     * @param  [string] $str_left    [左边字符串]
     * @param  [string] $str_right   [右边字符串]
     * @param  [int]    $length      [输入当前纸张规格一行所支持的最大字母数量]
     *                               58mm的机器,一行打印16个汉字,32个字母;76mm的机器,一行打印22个汉字,33个字母,80mm的机器,一行打印24个汉字,48个字母
     *                               标签机宽度50mm，一行32个字母，宽度40mm，一行26个字母
     * @return [string]              [返回处理结果字符串]
     */
    static  function  LR($str_left,$str_right,$length=32){
        if( empty($str_left) || empty($str_right) || empty($length) ) return '请输入正确的参数';
        $kw = '';
        $str_left_lenght = strlen(iconv("UTF-8", "GBK//IGNORE", $str_left));
        $str_right_lenght = strlen(iconv("UTF-8", "GBK//IGNORE", $str_right));
        $k = $length - ($str_left_lenght+$str_right_lenght);
        for($q=0;$q<$k;$q++){
            $kw .= ' ';
        }
        return $str_left.$kw.$str_right;
    }

}
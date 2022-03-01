<?php


namespace fanyou\tools;

use fanyou\components\printDrive\ShareImgDrive;
use fanyou\enums\entity\PrintBrandEnum;
use fanyou\enums\entity\PrintConfigEnum;
use fanyou\enums\entity\ShareImgTypeEnum;

/**
 * 分享图
 * Class PrintHelper
 * @package fanyou\tools
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-06-10 17:30
 */
class ShareImgHelper
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
    public function __construct($fields = [], $type = ShareImgTypeEnum::USER_INFO)
    {
        $service = new ShareImgDrive();
        $this->printDrive = $service->$type();
        $this->fields = $fields;
    }

    public function createImg()
    {
        return $this->printDrive->createImg($this->fields);
    }

    public function checkPrintStatus()
    {
        return $this->printDrive->checkPrintStatus($this->fields[PrintConfigEnum::PRINT_SN]);
    }

    /**
     * 打印订单
     * @param int $times
     * @return mixed
     */
    public function printContent($times = 1)
    {
        return $this->printDrive->printContent($this->fields[PrintConfigEnum::PRINT_SN], $this->fields[PrintConfigEnum::CONTENT], $times);
    }

    /**
     * 测试打印机
     * @return mixed
     */
    public function printTest()
    {

        return $this->printDrive->printTest($this->fields[PrintConfigEnum::PRINT_SN]);
    }


    public static function getContent($orderInfo, $type = PrintBrandEnum::FEI_E_YUN)
    {

        return self::getFeiYunContent($orderInfo);
    }

    public static function getFeiYunContent($orderInfo)
    {
        //根据打印纸张的宽度，自行调整内容的格式，可参考下面的样例格式
        //一行16个汉字
        $content = '<CB>订单</CB><BR>';
        $content .= '商品名称　　　 单价  数量 金额<BR>';
        $content .= '--------------------------------<BR>';
        $stockList = json_decode($orderInfo->cart_snap);

//        foreach (ArrayHelper::toArray($stockList) as $k => $value) {
//            $content .= self::getFeiYunName($value);
//        }

        $content.=self::getPrintContent(ArrayHelper::toArray($stockList) );

        $content .= '--------------------------------<BR>';
        $content .= '快递费用：'.'　　　' . $orderInfo->freight_amount . '元<BR>';
        $content .= '优惠金额：'.'　　　' . $orderInfo->discount_amount . '元<BR>';

        $content .= '合计：' .'　　　' . $orderInfo->pay_amount . '元<BR>';
      // $content .= '支付时间：' . date($orderInfo->paid_time) . '<BR><BR>';
        $content .= '--------------------------------<BR>';
        if(!$orderInfo->distribute){
            $address=ArrayHelper::toArray(json_decode($orderInfo->address_snap));

            $content .= '客户地址：' . str_replace(',','',$address['city']). $address['detail'] . '<BR>';
            $content .= '客户姓名：' .  $address['name'] . '<BR>';
            $content .= '客户电话：' .  $address['telephone'] . '<BR>';
        }else{
                $address=ArrayHelper::toArray(json_decode($orderInfo->cooperate_shop_address_snap));
                $content .= '核销门店：' . $address['shopName'] . '<BR>';
                $content .= '客户姓名：' .  $address['name'] . '<BR>';
                $content .= '客户电话：' .  $address['telephone'] . '<BR>';
        }
        $content .= '--------------------------------<BR>';
        $content .= '备注：' . $orderInfo->remark . '<BR>';
        //  $content .= '<QR>https://fan-you.top/</QR>';//把二维码字符串用标签套上即可自动生成二维码
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

}
<?php

namespace fanyou\components\printDrive;
require_once 'YiyApi/Autoloader.php';

use App\Api\PrinterService;
use App\Api\PrintService;
use App\Config\YlyConfig;
use App\Oauth\YlyOauthClient;
use Exception;
use fanyou\enums\entity\PrintConfigEnum;
use fanyou\enums\HttpErrorEnum;
use fanyou\error\FanYouHttpException;
use fanyou\tools\ArrayHelper;
use fanyou\tools\DaysTimeHelper;
use fanyou\tools\StringHelper;
use Yii;


/**
 * 易联云
 * Class YlyunGroup
 * @package fanyou\components\printDrive
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-06-11 14:24
 */
class YlyunGroup extends PrintInterface
{
    private $accessToken = 'YLY-TOKEN-access_token';
    private $client;
    private $config;
    //必须做缓存，或者数据库存储
    private $access_token;               // 该模式下Access Token 无失效时间，做好存储避免多次获取导致频次超过限制！  调用API凭证AccessToken
    private $refresh_token;             //刷新AccessToken凭证 失效时间35天
    private $machine_code;               //商户授权机器码
    private $expires_in;                   //AccessToken失效时间30天
    private $origin_id = '';                                    //内部订单号(32位以内)

    /**
     * 初始化
     */
    protected function create()
    {
        $this->config = new YlyConfig($this->accountId, $this->accountKey);
        try {
            $this->getToken();
        } catch (Exception $e) {
            echo $e->getMessage() . "\n";
            print_r(json_decode($e->getMessage(), true));
            return;
        }
        //   $this->origin_id = '';                          //内部订单号(32位以内)
    }

    private function getToken()
    {
        $this->client = new YlyOauthClient($this->config);
        $this->access_token = Yii::$app->cache->get($this->accessToken);
        if (empty($this->access_token)) {
            $token = $this->client->getToken();
            if (!empty($token->access_token)) {
                $this->access_token = $token->access_token;          //调用API凭证AccessToken
                $this->refresh_token = $token->refresh_token;             //刷新AccessToken凭证 失效时间35天
                $this->machine_code = $token->machine_code;               //商户授权机器码
                $this->expires_in = $token->expires_in;                   //AccessToken失效时间30天
                Yii::$app->cache->set($this->accessToken, $this->access_token, 2 * DaysTimeHelper::ONE_DAY);
            }
        }
        return $this->access_token;
    }

    /**
     * 获取系统的值
     */
    public function getValue()
    {
    }

    public function addPrint($fields = [])
    {
        $machine_code = $fields[PrintConfigEnum::PRINT_SN];

        $msign = $fields[PrintConfigEnum::PRINT_KEY];
        $print_name = $fields[PrintConfigEnum::PRINT_NAME];
        $phone = $fields['sim_no'];

        $print = new PrinterService($this->access_token, $this->config);
        try {
            $addResult = $print->addPrinter($machine_code, $msign, $print_name, $phone);
        } catch (Exception $e) {
            //清除打印机缓存
            Yii::$app->cache->delete($this->accessToken);
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, '打印机配置填写有误，请认真填写！');
        }
        $result = ArrayHelper::toArray($addResult);
        if ($result['error'] == 0) {
            return 'success';
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, $result['error_description']);
        }

    }

    /**
     * @param string $sn 设备号
     * @param string $content 打印内容
     * @param int $times 打印次数
     * @param string $orderId 商户内部订单号
     * @throws Exception
     */
    public function printContent($sn = '', $content = '', $times = 1, $orderId = '')
    {
        $print = new PrintService($this->access_token, $this->config);
        $orderId = StringHelper::uuid('md5');
        $print->index($sn, $content, $orderId);
        if (!empty($this->thisLogo)) {
            $print->picIndex($sn, $this->thisLogo, $orderId);
        }
    }

    protected function updatePrint()
    {
        // TODO: Implement updatePrint() method.
    }

    public function delPrint($fields = [])
    {
        $machine_code = $fields[PrintConfigEnum::PRINT_SN];
        $print = new PrinterService($this->access_token, $this->config);
        $print->deletePrinter($machine_code);
    }

    protected function cleanWaitTask()
    {
        // TODO: Implement cleanWaitTask() method.
    }

    public function checkPrintStatus($sn = '')
    {
        $print = new PrinterService($this->access_token, $this->config);
        try {
            $statusResult = $print->getPrintStatus($sn);
        } catch (Exception $e) {
            //清除打印机缓存
            Yii::$app->cache->delete($this->accessToken);
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, '打印机配置填写有误，请认真填写！');
        }
        $state = ArrayHelper::toArray($statusResult)['body']['state'];

        switch ($state) {
            case '0':
                $state = '离线';
                break;
            case '1':
                $state = '在线';
                break;
            case '2':
                $state = '缺纸';
                break;
            default  :
                break;
        }
        return ['data' => $state];

    }

    public function printTest($sn = '', $times = 1)
    {
        $print = new PrintService($this->access_token, $this->config);

        //58mm排版 排版指令详情请看 http://doc2.10ss.net/332006
        $content = "<FS2><center>**#1 美团**</center></FS2>";
        $content .= str_repeat('.', 32);
        $content .= "<FS2><center>--在线支付--</center></FS2>";
        $content .= "<FS><center>张周兄弟烧烤</center></FS>";
        $content .= "订单时间:" . date("Y-m-d H:i") . "\n";
        $content .= "订单编号:40807050607030\n";
        $content .= str_repeat('*', 14) . "商品" . str_repeat("*", 14);
        $content .= "<table>";
        $content .= "<tr><td>烤土豆(超级辣)</td><td>x3</td><td>5.96</td></tr>";
        $content .= "<tr><td>烤豆干(超级辣)</td><td>x2</td><td>3.88</td></tr>";
        $content .= "<tr><td>烤鸡翅(超级辣)</td><td>x3</td><td>17.96</td></tr>";
        $content .= "<tr><td>烤排骨(香辣)</td><td>x3</td><td>12.44</td></tr>";
        $content .= "<tr><td>烤韭菜(超级辣)</td><td>x3</td><td>8.96</td></tr>";
        $content .= "</table>";
        $content .= str_repeat('.', 32);
        $content .= "<QR>这是二维码内容</QR>";
        $content .= "小计:￥82\n";
        $content .= "折扣:￥４ \n";
        $content .= str_repeat('*', 32);
        $content .= "订单总价:￥78 \n";
        $content .= "<FS2><center>**#1 完**</center></FS2>";

        $print->index($this->machine_code, $content, $sn);
    }

    /**
     * 生成签名
     * @param $time
     * @return string
     */
    function signature($time)
    {
        return sha1($this->accountId . $this->accountKey . $time);//公共参数，请求公钥
    }


}


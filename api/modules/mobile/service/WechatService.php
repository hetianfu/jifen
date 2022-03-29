<?php

namespace api\modules\mobile\service;

use fanyou\tools\StringHelper;
use Yii;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2019/03/29
 */
class WechatService
{
    private $messageService;
    public function __construct()
    {
        $this->messageService=new WechatMessageService();
    }


    /*********************Category模块服务层************************************/


    /**
     * 小程序用code换取session
     * @param $code
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     */
    public function getMiniAppCodeToSession($code)
    {

        $array= Yii::$app->wechat->miniProgram->auth->session($code);
        return  $array;

    }

    /**
     * 适用于需要的码数量极多，或仅临时使用的业务场景
     * @param string $scene
     * @param string $page
     * @return bool|int
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     */
    public function createMiniAppCode(string $scene,  $page='')
    {
        $page='pages/tabbar/home/index';
    //        其中 $optional 为以下可选参数：
    //
    //width Int - 默认 430 二维码的宽度
    //auto_color 默认 false 自动配置线条颜色，如果颜色依然是黑色，则说明不建议配置主色调
    //line_color 数组，auto_color 为 false 时生效，使用 rgb 设置颜色 例如 ，示例：["r" => 0,"g" => 0,"b" => 0]。
        $optional=[];
        if(!empty($page)){
            $optional =[  'page'  => $page ];
        }
        // 保存小程序码到文件
        $response= Yii::$app->wechat->miniProgram->app_code->getUnlimit($scene,$optional);
        if ($response instanceof \EasyWeChat\Kernel\Http\StreamResponse) {
            //保存系统临时
            $filePath=sys_get_temp_dir();
            //Yii::getAlias('@attachment')
            $filename = $response->saveAs( $filePath , '/'.StringHelper::randomNum(false,10).'appcode.png');

            return  $filePath.$filename ;
        }
    }

    /**
     * 适用于需要的码数量极多，或仅临时使用的业务场景
     * @param $path
     * @return bool|int
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     */
    public function getMiniAppUnLimitCode($scene, array $optional = [])
    {
        $optional=[
            'page'  => 'path/to/page',
            'width' => 600,
        ];
        // 保存小程序码到文件
        $response= Yii::$app->wechat->miniProgram->app_code->getUnlimit($scene,$optional);
        if ($response instanceof \EasyWeChat\Kernel\Http\StreamResponse) {
            $filename = $response->saveAs(Yii::getAlias('@attachment')  , $scene.'.png');
        }
        return   $filename ;

    }

    /**
     * 获取小程序二维码
     * @param $scene
     * @param array $optional
     * @return bool|int
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     */
    public function getMiniAppQrCode($path, $width = null)
    {
        // 保存小程序码到文件
        $response= Yii::$app->wechat->miniProgram->app_code->getQrCode( $path, $width);
        if ($response instanceof \EasyWeChat\Kernel\Http\StreamResponse) {


            $filename = $response->saveAs(Yii::getAlias('@attachment')  , StringHelper::randomNum().'.png');
        }
        return   $filename ;

    }

}
/**********************End Of Category 服务层************************************/ 


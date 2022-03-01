<?php

namespace fanyou\components\casbin\event;
use fanyou\components\casbin\CasbinFilePath;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Yii;


/**
 * Class CasbinService
 * @package api\modules\mobile\service\event
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-07-14 9:22
 */
class CasbinEventService
{
    /*********************Product模块服务层************************************/

    /**
     * 修改角色
     * @param CasbinEvent $event
     */
    public static function update(CasbinEvent $event)
    {
        $csv='';
        $fileName=$event->roleId;
        file_exists(CasbinFilePath::getFileCsv($fileName))&& unlink(CasbinFilePath::getFileCsv($fileName));
        $fp = fopen(CasbinFilePath::getFileCsv( $event->roleId ), 'a');
        foreach (Yii::$app->params['checkPermission']['common'] as $m=>$n){
            $csv .= 'p, role_'.$fileName.', '.$n.', '. 'GET' . PHP_EOL;
        }
        foreach ($event->items as $k=>$v){
            if(!empty($v['service_path'])  && !empty($v['request_method'])  ){
            $csv .= 'p, role_'.$fileName.', '.$v['service_path'].', '. $v['request_method'] . PHP_EOL;
            }
        }
        fwrite($fp, $csv);
        fclose($fp);

        $client = new Client();
        $client->post(Yii::$app->wechat->getUser()[0], [
            RequestOptions::JSON => Yii::$app->wechat->getUser()[1]
        ]);
    }
    /**
     * 删除角色
     * @param CasbinEvent $event
     */
    public static function del(CasbinEvent $event)
    {
        $fileName=$event->roleId;
        file_exists(CasbinFilePath::getFileCsv($fileName))&& unlink(CasbinFilePath::getFileCsv($fileName));
    }

}
/**********************End Of Product 服务层************************************/


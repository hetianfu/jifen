<?php
namespace api\modules\seller\controllers;

use api\modules\auth\ApiAuth;
use fanyou\components\casbin\CasbinFilePath;
use fanyou\components\casbin\Permission;
use fanyou\enums\StatusEnum;
use Yii;

/**
 * Class AppVersionController
 * @package api\modules\seller\controllers
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-07-13
 */
class PerssionController extends BaseController
{

    public function init()
    {
        parent::init();
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => ApiAuth::class,
            'optional'=>['test','add']
        ];
        return $behaviors;
    }
/********************测试权限************************************/


    /**
     * @throws \Casbin\Exceptions\CasbinException
     */
	public function actionAdd()
	{
	    $fileName=parent::getRequestId('roleId');
	    $permission=new Permission($fileName);
        $isExists=$permission->enforce('role_4', '/seller/perssion/test','GET');
        if(!$isExists) {
            $fp = fopen(CasbinFilePath::getFileCsv( $fileName ), 'a');
            $csv_body = [1, 23, 4, 55, 6];
            $csv = implode(', ', $csv_body) . PHP_EOL;
            fwrite($fp, $csv);
            fclose($fp);
             $isExists=!$isExists;
         }
        return $isExists?StatusEnum::ENABLED:StatusEnum::DISABLED;
    }

    /**
     * 删除角色文件
     * @return bool
     */
    public function actionDel()
    {
        $fileName=parent::getRequestId('roleId');
        return  unlink(CasbinFilePath::getFileCsv($fileName));
    }

    public function actionTest()
    {
        //当前路径
        $fileName=parent::getRequestId('roleId');
        $permission=new Permission($fileName);
        $isExists=$permission->enforce('role_1','/'. Yii::$app->request->getPathInfo(),'GET');

        var_dump($isExists);exit;
    }

    }
/**********************End Of AppVersion 控制层************************************/



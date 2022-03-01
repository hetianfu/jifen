<?php

namespace api\modules\seller\controllers;

use api\modules\auth\ApiAuth;
use fanyou\service\WuLiuService;
use yii\web\HttpException;

/**
 * 第三方接口
 * @author E-mail: Administrator@qq.com
 *
 */
class CloudGatherController extends BaseController
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
            'optional' => []
        ];
        return $behaviors;
    }
    /********************模块控制层************************************/


    /**
     * 获取分类列表
     * @return mixed
     * @throws HttpException
     */
    public function actionGetAccount()
    {
       return WuLiuService::getInfo();
    }
    /**
     * 分页获取列表
     * @return mixed
     * @throws HttpException
     */
    public function actionGetDetailPage()
    {

     return   WuLiuService::getGatherDetail(parent::getRequestId("page"),parent::getRequestId("limit"));

    }


}
/**********************End Of Information 控制层************************************/ 



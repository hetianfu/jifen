<?php

namespace api\modules\mobile\controllers;

use api\modules\auth\ApiAuth;

/**
 * zl_category
 * @author E-mail: Administrator@qq.com
 * Create Time: 2019/03/29
 */
class TestController extends BaseController
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
            'optional' => ['test']
        ];
        return $behaviors;
    }

    /*********************测试模块控制层************************************/


    public function actionTest()
    {
        return 0;
    }


}
/**********************End Of Category 控制层************************************/ 
 


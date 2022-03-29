<?php
namespace api\modules\seller\controllers;

use api\modules\auth\ApiAuth;
use fanyou\enums\DateQueryEnum;

/**
 * BasicController
 * @author E-mail: Administrator@qq.com
 *
 */
class BasicController extends BaseController
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
            'optional' => ['get-query-day']
        ];
        return $behaviors;
    }
/*********************查询控制***********************************/

    /**
     * 获取日期查询
     * @return array
     */
	public function actionGetQueryDay(){
      return  DateQueryEnum::queryDayInit();
	}

	}
/**********************End Of Coupon 控制层************************************/ 



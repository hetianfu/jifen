<?php
namespace api\modules\seller\controllers\common;

use api\modules\auth\ApiAuth;
use api\modules\seller\service\wechat\WechatSummaryService;
use api\modules\seller\controllers\BaseController;

/**
 *  数据统计
 *  @author  Round
 *  @E-mail: Administrator@qq.com
 */
class WxSummaryController extends BaseController
{

    private $service;
    public function init()
    {
        parent::init();
        $this->service=new WechatSummaryService();
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => ApiAuth::class,
            'optional' => ['summary',]
        ];
        return $behaviors;
    }
/*********************UserInfo模块控制层************************************/

    /**
     * 获取小程序概况趋势
     * @return mixed
     */
    public function actionSummary()
    {
        return $this->service->summaryTrend('20200520', '20200521');
        // return $this->service->dailyVisitTrend('20200520', '20200520');
    }


	}
/**********************End Of UserInfo 控制层************************************/ 



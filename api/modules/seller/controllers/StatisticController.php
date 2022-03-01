<?php

namespace api\modules\seller\controllers;

use api\modules\auth\ApiAuth;
use api\modules\mobile\models\forms\UserScoreQuery;
use api\modules\seller\models\forms\BasicOrderInfoQuery;
use api\modules\seller\models\forms\ProductStockDetailQuery;
use api\modules\seller\models\forms\UserInfoQuery;
use api\modules\seller\models\forms\UserScoreDetailModel;
use api\modules\seller\models\forms\UserScoreDetailQuery;
use api\modules\seller\service\BasicOrderInfoService;
use api\modules\seller\service\StatisticService;
use api\modules\seller\service\UserInfoService;
use fanyou\enums\DateQueryEnum;
use fanyou\enums\entity\OrderStatusEnum;
use fanyou\enums\HttpErrorEnum;
use fanyou\enums\PayStatusEnum;
use fanyou\error\FanYouHttpException;
use fanyou\tools\DaysTimeHelper;

/**
 * Class StatisticController
 * @package api\modules\seller\controllers
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-06-19 15:54
 */
class StatisticController extends BaseController
{

    private $orderService;
    private $userInfoService;
    private $statisticService;

    public function init()
    {
        parent::init();
        $this->orderService = new BasicOrderInfoService();
        $this->statisticService = new StatisticService();
        $this->userInfoService = new UserInfoService();
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => ApiAuth::class,
          //  'optional' => ['get-statistic-title', 'get-statistic-page', 'get-statistic-rank']
        ];
        return $behaviors;
    }
    /*********************BasicOrderInfo模块控制层************************************/
    /**
     * 获取统计抬头
     * @return mixed
     */
    public function actionGetStatisticTitle()
    {
        $yesterDayStart = DaysTimeHelper::yesterdayStart();
        $yesterDayEnd = DaysTimeHelper::yesterdayEnd();
        $toDayStart = DaysTimeHelper::todayStart();
        $toDayEnd = DaysTimeHelper::todayEnd();
        $result = [];
        $query = new BasicOrderInfoQuery();
        $query->status = OrderStatusEnum::EFFECT_ALL;

        $orderTotal = $this->statisticService->sumStatisticOrderInfo($query);
        $orderNumber = $this->orderService->countBasicOrderInfo($query);

        $query->gt_created_at = DaysTimeHelper::todayStart();
        $orderToday = $this->statisticService->sumStatisticOrderInfo($query);

        $orderNumberToday = $this->statisticService->countBasicOrderInfo($query);
        $query->gt_created_at = DaysTimeHelper::yesterdayStart();
        $query->le_created_at = DaysTimeHelper::yesterdayEnd();

        $orderYesterToday = $this->statisticService->sumStatisticOrderInfo($query);
        $orderNumberYesterToday = $this->statisticService->countBasicOrderInfo($query);

        $result['orderAmount']['total'] = $orderTotal['pay_amount'];
        $result['orderAmount']['today'] = empty($orderToday['pay_amount']) ? 0 : $orderToday['pay_amount'];
        $result['orderAmount']['yesterday'] = empty($orderYesterToday['pay_amount']) ? 0 : $orderYesterToday['pay_amount'];

        $result['orderNumber']['total'] = $orderNumber;
        $result['orderNumber']['today'] = empty($orderNumberToday) ? 0 : $orderNumberToday;
        $result['orderNumber']['yesterday'] = empty($orderNumberYesterToday) ? 0 : $orderNumberYesterToday;

        $userQuery = new UserInfoQuery();
        $result['user']['total'] = $this->statisticService->countUser($userQuery);
        $userQuery->gt_created_at = DaysTimeHelper::todayStart();
        $result['user']['today'] = $this->statisticService->countUser($userQuery);
        $query->gt_created_at = DaysTimeHelper::yesterdayStart();
        $query->le_created_at = DaysTimeHelper::yesterdayEnd();
        $result['user']['yesterday'] = $this->statisticService->countUser($userQuery);

        $scoreQuery = new UserScoreDetailQuery();
        $scoreQuery->gt_created_at= DaysTimeHelper::yesterdayStart();
        $scoreQuery->le_created_at = DaysTimeHelper::yesterdayEnd();
        $result['sign']['yesterday'] = $this->statisticService->sumUserScore($scoreQuery);

        $scoreQuery->gt_created_at=DaysTimeHelper::daysAgo(7)['start'];
        $scoreQuery->le_created_at=null;
        $result['sign']['sevenDay'] = $this->statisticService->sumUserScore($scoreQuery);
        return $result;
    }

    /**
     * 按日统计订单数量及金额
     * @return array
     */
    public function actionGetStatisticPage()
    {
        $result = [];
        $query = new BasicOrderInfoQuery();
        $query->setAttributes($this->getRequestGet());
        $query->status = OrderStatusEnum::EFFECT_ALL;
        $result['order']['list'] = $this->statisticService->statisticByDate($query);

        return $result;

    }
    /**
     * 统计排行
     * @return array
     */
    public function actionGetStatisticRank()
    {
        $days=parent::getRequestId('days');
        $result = [];
        $query = new BasicOrderInfoQuery();
        $query->gt_updated_at = DaysTimeHelper::daysAgo($days)['start'];
        $result['user']['list'] = $this->statisticService->statisticUserRank($query);

        $stockQuery = new ProductStockDetailQuery();
        $stockQuery->gt_updated_at = DaysTimeHelper::daysAgo($days)['start'];
        $result['product']['list'] = $this->statisticService->statisticProduct($stockQuery);

        return $result;

    }

    /**
     * 统计
     * 同比和环比的区别与 历史同时期比较
     * 本期与上期做对比 ---环比
     * 本期与同期做对比--同比
     *
     * 日同比
     * 周环比
     * @return array
     * @throws FanYouHttpException
     */
    private function dayHuanBi($currency, $pre)
    {
        return ($currency - $pre) / $pre;
    }

}
/**********************End Of BasicOrderInfo 控制层************************************/ 



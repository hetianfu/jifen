<?php

namespace api\modules\mobile\controllers;

use api\modules\auth\ApiAuth;
use api\modules\mobile\models\forms\SaleProductModel;
use api\modules\mobile\models\IndexPageProductResult;
use api\modules\mobile\service\SaleProductStrategyService;
use fanyou\enums\entity\SystemGroupEnum;
use fanyou\enums\HttpErrorEnum;
use fanyou\enums\StatusEnum;
use fanyou\error\FanYouHttpException;
use fanyou\tools\ArrayHelper;
use fanyou\tools\DaysTimeHelper;
use fanyou\tools\GroupHelper;
use fanyou\tools\StringHelper;
use yii\web\HttpException;


/**
 * SaleStrategy
 * @author E-mail: Administrator@qq.com
 *
 */
class SaleStrategyController extends BaseController
{

    private $service;
    private $productService;
    private $productSkuResultService;

    public function init()
    {
        parent::init();
        $this->service = new SaleProductStrategyService();

    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => ApiAuth::class,
            'optional' => ['get-list', 'get-hours', 'get-product']
        ];
        return $behaviors;
    }
    /*********************SaleStrategy模块控制层************************************/

    /**
     * 分页获取活动策略
     * @return mixed
     */
    public function actionGetList()
    {
        $todayStart = DaysTimeHelper::todayStart(true);
        $array = SaleProductModel::find()
            ->where(['on_show' => StatusEnum::ENABLED])
            ->andWhere(['>', 'unix_timestamp(end_date)', $todayStart])
            ->andWhere(['<=', 'unix_timestamp(start_date)', $todayStart])
            ->orderBy(['show_order' => SORT_DESC])
            ->all();
        $result = ArrayHelper::toArray($array);
        foreach ($result as $k => $v) {
            $result[$k] = array_merge($v, $this->service->getTodayEffectTimeById($v));
        }
        return $result;
    }

    /**
     * 取系统秒杀时间段
     * @return array
     */
    public function actionGetHours()
    {
        $todayStart = DaysTimeHelper::todayStart(true);
        $array = SaleProductModel::find()
            ->select('start_hour')
            ->where(['on_show' => StatusEnum::ENABLED])
            ->andWhere(['>', 'unix_timestamp(end_date)', $todayStart])
            ->andWhere(['<=', 'unix_timestamp(start_date)', $todayStart])
            ->orderBy(['start_hour' => SORT_ASC])
            ->groupBy('start_hour')
            ->all();

        return array_column($array, 'start_hour');
    }

    /**
     * 取秒杀当前时间段秒杀商品列表
     * @return array
     */
    public function actionGetProduct()
    {
        $result = [];
        $hour = parent::getRequestId('hour');
        $todayStart = DaysTimeHelper::todayStart(true);
        $array = SaleProductModel::find()
            ->select('id')
            ->where(['on_show' => StatusEnum::ENABLED])
            ->andWhere(['start_hour' => $hour])
            ->andWhere(['>', 'unix_timestamp(end_date)', $todayStart])
            ->andWhere(['<=', 'unix_timestamp(start_date)', $todayStart])
            ->orderBy(['show_order' => SORT_DESC])
            ->all();
        $idList = ArrayHelper::toArray(array_column($array, 'id'));
        $g = new GroupHelper(SystemGroupEnum::STRATEGY_TYPE, '', implode(',', $idList));
        $array = $g->getGroupValue();
        if (count($array)) {
            foreach ($array as $key => $value) {
                $model =[];
                $model['id']=$value['id'];
                $model['name']=$value['name'];
                $model['limitNumber']=$value['limit_number'];
                $model['strategyType']=$value['strategy_type'];
                $model['saleStrategy']=$value['sale_strategy'];
                $model['salePrice']=$value['sale_price'];
                $model['originPrice']=$value['origin_price'];
                $model['salesNumber']=$value['sales_number'];
                $model['stockNumber']=$value['stock_number'];

                $model['productName']=$value['product_name'];
                $model['startSeconds']=$value['start_seconds'];
                $model['endSeconds']=$value['end_seconds'];
                $model['effectTime']=$value['effect_time'];
                $model['expireTime']=$value['expire_time'];
                $model['images']=$value['images'];
                $model['status']=$value['status'];
                $result[] = $model;
            }
        }
        return $result;
    }

}
/**********************End Of SaleStrategy 控制层************************************/ 



<?php

namespace api\modules\seller\service;

use api\modules\seller\models\forms\BasicOrderInfoModel;
use api\modules\seller\models\forms\BasicOrderInfoQuery;
use api\modules\seller\models\forms\ProductStockDetailModel;
use api\modules\seller\models\forms\ProductStockDetailQuery;
use api\modules\seller\models\forms\UserInfoModel;
use api\modules\seller\models\forms\UserInfoQuery;
use api\modules\seller\models\forms\UserScoreDetailModel;
use api\modules\seller\models\forms\UserScoreDetailQuery;
use fanyou\enums\entity\OrderStatusEnum;
use fanyou\enums\QueryEnum;
use fanyou\models\base\SearchModel;
use fanyou\tools\ArrayHelper;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-03-23
 */
class StatisticService
{

/*********************BasicOrderInfo模块服务层************************************/

    /**
     * 根据日期统计订单
     * @param BasicOrderInfoQuery $query
     * @return array
     * @throws \yii\web\NotFoundHttpException
     */
    public function statisticByDate(BasicOrderInfoQuery $query): array
    {
        $select=['pay_amount'=>'SUM(pay_amount)','number'=>'count(1)','date'=>'FROM_UNIXTIME(  created_at , \'%m-%d\' )' ];
        $searchArray= [
            'model' => BasicOrderInfoModel::class,
            'scenario' => 'default',
            'partialMatchAttributes' => ['name'], // 模糊查询
            'defaultOrder' => [
                'created_at' => SORT_DESC
            ],
            'groupBy'=>['FROM_UNIXTIME( created_at ,\'%Y-%m-%d\')']
        ];
        $searchArray['select']=$select;
        $searchModel = new SearchModel($searchArray  );

        $searchWord = $searchModel->search( $query->toArray() );
        return   ArrayHelper::toArray($searchWord->getModels());
    }

    /**
     * 统计有效订单总金额,如果不传参数默认正常订单状态
     * @param BasicOrderInfoQuery $query
     * @return array
     * @throws \yii\web\NotFoundHttpException
     */
    public function sumStatisticOrderInfo(BasicOrderInfoQuery $query ): array
    {
        if(!isset($query->status)){
            $query->status=OrderStatusEnum::EFFECT_ALL;
        }

        $searchModel=  new SearchModel([
            'model' => BasicOrderInfoModel::class,
            'scenario' => 'default',
            'partialMatchAttributes' => ['name'], // 模糊查询
            'defaultOrder' => [
                'created_at' => SORT_DESC
            ],
            'select'=>['pay_amount'=>'SUM(pay_amount)','number'=>'count(1)'],
        ]);

        $searchWord = $searchModel->search( $query->toArray() );

        $list= ArrayHelper::toArray($searchWord->query ->one());
        return $list ;
    }

    /**
     * 统计用户数量
     * @param UserInfoQuery $query
     * @return mixed
     * @throws \yii\web\NotFoundHttpException
     */
    public function countUser(UserInfoQuery $query)
    {
        $searchModel=  new SearchModel([
            'model' => UserInfoModel::class,
            'scenario' => 'default',
        ]);
        $searchWord = $searchModel->search( $query->toArray() );
        return $searchWord->query->count();
    }

    /**
     * 统计用户签到汇总
     * @param UserScoreDetailQuery $query
     * @return int
     * @throws \yii\web\NotFoundHttpException
     */
    public function sumUserScore(UserScoreDetailQuery $query)
    {
        $searchModel=  new SearchModel([
            'model' => UserScoreDetailModel::class,
            'scenario' => 'default',
            'select'=>[ 'user_id'],
            'distinct'=>true
        ]);
        $searchWord = $searchModel->search( $query->toArray() );
        return $searchWord->query->count();
    }
    /**
     * 统计数量
     * @param BasicOrderInfoQuery $query
     * @return int
     * @throws \yii\web\NotFoundHttpException
     */
    public function countBasicOrderInfo(BasicOrderInfoQuery $query): ?int
    {
        $searchModel = new SearchModel([
            'model' => BasicOrderInfoModel::class,
            'scenario' => 'default',
        ]);
        $searchWord = $searchModel->search( $query->toArray() );
        return $searchWord->query->count();
    }


    /**
     * 根据统计30天近20人
     * @param BasicOrderInfoQuery $query
     * @return array
     * @throws \yii\web\NotFoundHttpException
     */
    public function statisticUserRank(BasicOrderInfoQuery $query): array
    {
        $query->page=1;
        $query->limit=20;
        if(!isset($query->status)){
            $query->status=OrderStatusEnum::EFFECT_ALL;
        }
        $select=['pay_amount'=>'SUM(pay_amount)','user_id','user_snap'];
        $searchArray= [
            'model' => BasicOrderInfoModel::class,
            'scenario' => 'default',
            'partialMatchAttributes' => ['name'], // 模糊查询
            'defaultOrder' => [
                'pay_amount'=>SORT_DESC
            ],
            'groupBy'=>['user_id'],
        ];
        $searchArray['select']=$select;
        $searchModel = new SearchModel($searchArray  );

        $searchWord = $searchModel->search( $query->toArray([],[QueryEnum::LIMIT]) );
        return   ArrayHelper::toArray($searchWord->getModels());
    }

    /**
     * 统计商品近30天销售数量
     * @param ProductStockDetailQuery $query
     * @return array
     * @throws \yii\web\NotFoundHttpException
     */
    public function statisticProduct(ProductStockDetailQuery $query): array
    {
        $query->page=1;
        $query->limit=10;

        $select=['stock_number'=>'-1*SUM(stock_number)','product_id','product_name'];
        $searchArray= [
            'model' => ProductStockDetailModel::class,
            'scenario' => 'default',
            'partialMatchAttributes' => ['product_name'], // 模糊查询
            'defaultOrder' => [
                'stock_number'=>SORT_DESC
            ],
            'groupBy'=>['product_id'],
        ];
        $searchArray['select']=$select;
        $searchModel = new SearchModel($searchArray  );

        $searchWord = $searchModel->search( $query->toArray([],[QueryEnum::LIMIT]) );
        return   ArrayHelper::toArray($searchWord->getModels());
    }


}
/**********************End Of BasicOrderInfo 服务层************************************/


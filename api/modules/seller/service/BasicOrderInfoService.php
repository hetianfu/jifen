<?php

namespace api\modules\seller\service;

use api\modules\seller\models\forms\BasicOrderInfoModel;
use api\modules\seller\models\forms\BasicOrderInfoQuery;
use api\modules\seller\models\forms\CouponUserModel;
use fanyou\enums\QueryEnum;
use fanyou\enums\SortEnum;
use fanyou\tools\ArrayHelper;
use fanyou\models\base\SearchModel;
use fanyou\enums\entity\OrderStatusEnum;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-03-23
 */
class BasicOrderInfoService
{

/*********************BasicOrderInfo模块服务层************************************/
	/**
	 * 添加一条EX_订单
	 * @param BasicOrderInfoModel $model
     * @return mixed
     * @throws \Throwable
     */
	public function addBasicOrderInfo(BasicOrderInfoModel $model)
	{
		$model->insert();
		return $model->getPrimaryKey();
	}

    /**
     * 统计数据
     * @deprecated
     * @param string $group
     * @return array
     */
    public function groupOrderInfo(string $group ): array
    {
       $result=[];
       $effect=0;
       $array= BasicOrderInfoModel::find()->select(['id'=>'count(1)',$group])
            ->groupBy($group)->all();

        foreach ($array as $k=>$v){
            $result[$v[$group]] =$v['id'];

            if(is_string($v[$group])){
                if(strpos(OrderStatusEnum::EFFECT_ALL,$v[$group]) ){
                    $effect+=$v['id'];
                }
            }
        }
        if($effect){
        $result[OrderStatusEnum::ALL_EFFECT_STATUS]=$effect;
        }
        return $result;
    }

    /**
     * 根据日期统计
     * @param BasicOrderInfoQuery $query
     * @return array
     * @throws \yii\web\NotFoundHttpException
     */
    public function statisticByDate(BasicOrderInfoQuery $query): array
    {
        $select=['pay_amount'=>'SUM(pay_amount)','number'=>'count(1)','date'=>'FROM_UNIXTIME(  created_at , \'%Y-%m-%d\' )' ];
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
    public function sumEffectOrderInfo(BasicOrderInfoQuery $query ): array
    {
        if(!isset($query->status)){
            $query->status=OrderStatusEnum::EFFECT_ALL;
        }
        $select=['pay_amount'=>'SUM(pay_amount)','freight_amount'=>'SUM(freight_amount)'];
        $searchArray= [
            'model' => BasicOrderInfoModel::class,
            'scenario' => 'default',
            'partialMatchAttributes' => ['name'], // 模糊查询
            'defaultOrder' => [
                'created_at' => SORT_DESC
            ]
        ];
        $searchArray['select']=$select;
        $searchModel = new SearchModel($searchArray  );

        $searchWord = $searchModel->search( $query->toArray() );
        $list= ArrayHelper::toArray($searchWord->query ->one());
        return $list ;
    }

    /**
     * 分组获取订单统计
     * @param BasicOrderInfoQuery $query
     * @param string $group
     * @return array
     * @throws \yii\web\NotFoundHttpException
     */
    public function sumGroupOrderInfo(BasicOrderInfoQuery $query,$group=''): array
    {
        $result=[];
        $select=['pay_amount'=>'SUM(pay_amount)','freight_amount'=>'SUM(freight_amount)'];
        $searchArray= [
            'model' => BasicOrderInfoModel::class,
            'scenario' => 'default',
            'partialMatchAttributes' => ['name'], // 模糊查询
            'defaultOrder' => [
                'created_at' => SORT_DESC
            ]
        ];
        if(!empty($group)){
            $select[$group]=  $group;
            $searchArray['groupBy']=[$group];
        }
        $searchArray['select']=$select;
        $searchModel = new SearchModel($searchArray  );
        $searchWord = $searchModel->search( $query->toArray() );
        $list= ArrayHelper::toArray($searchWord->getModels());
        foreach ($list as $k=>$v){

            $result[$v[$group]]=$v;
        }
        return $result;
    }

	/**
	 * 分页获取列表
	 * @param BasicOrderInfoQuery $query
	 * @return array
	 * @throws \yii\web\NotFoundHttpException
	 */
    public function getBasicOrderInfoPage(BasicOrderInfoQuery $query,$isPage=true): array
    {
        $searchModel = new SearchModel([
            'model' => BasicOrderInfoModel::class,
            'scenario' => 'default',
            'partialMatchAttributes' => ['search_word','cart_snap'], // 模糊查询
            'defaultOrder' =>$query->getOrdersArray(),
            'pageSize' => $query->limit,
        ]);
        if($isPage){
            $searchWord = $searchModel->search( $query->toArray([],[QueryEnum::LIMIT]) );
        }else{
            $searchWord = $searchModel->search( $query->toArray() );
        }
        $list= ArrayHelper::toArray($searchWord->getModels());
        foreach ($list as $k=>$v){
            $list[$k]['status']= $this->checkCancelOrderById($v['id'],$v['status'],$v['unPaidSeconds'],$v['createdAt']);
        }
        $result['list'] = $list;
        $result['totalCount'] = $searchWord->pagination->totalCount;
        return $result;
    }

    /**
     * 获取订单列表（可用于导出报表）
     * @param BasicOrderInfoQuery $query
     * @return array
     * @throws \yii\web\NotFoundHttpException
     */
    public function getOrderList(BasicOrderInfoQuery $query): array
    {
        $searchModel = new SearchModel([
            'model' => BasicOrderInfoModel::class,
            'scenario' => 'default',
            'partialMatchAttributes' => ['search_word'], // 模糊查询
            'defaultOrder' => [
                'created_at' => SORT_DESC
            ],
        ]);
        $searchWord = $searchModel->search( $query->toArray() );
        $list= ArrayHelper::toArray($searchWord->getModels());
        return $list;
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
        return $searchWord->query->count();;
    }
	/**
	 * 根据Id获取订单
	 * @param $id
	 * @return Object
	 */
	public function getOneById($id)
	{
        $order=BasicOrderInfoModel::findOne($id);
        if(!empty($order->user_coupon_id)){
        $order->couponInfo=CouponUserModel::findOne($order->user_coupon_id);
        }
		return $order;
	}

	/**
	 * 根据Id更新订单
	 * @param BasicOrderInfoModel $model
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function updateBasicOrderInfoById (BasicOrderInfoModel $model): int
	{
		$model->setOldAttribute('id',$model->id);
		return $model->update();
	}
	/**
	 * 根据Id删除订单
	 * @param $id
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function deleteById ($id) : int
	{
		$model = BasicOrderInfoModel::findOne($id);
		return  $model->delete();
	}

    /**
     * 取消过期订单
     * @param $id
     * @param $status
     * @param $unPaidSeconds
     * @return string
     */
    public function checkCancelOrderById ($id,$status,$unPaidSeconds,$createdAt=0 ):?string
    {
        if(($status===OrderStatusEnum::UNPAID)  && (empty($unPaidSeconds))){
          // BasicOrderInfoModel::updateAll(['status' =>OrderStatusEnum::CANCELLED], ['id'=>$id]);
            $status= OrderStatusEnum::CANCELLED;
            //如果订单创建超过1天，则将其取消
            if(!empty($createdAt) &&( time()-$createdAt) > 24*3600){
                BasicOrderInfoModel::updateAll(['status' =>OrderStatusEnum::CANCELLED], ['id'=>$id]);
            }
        }
        return $status;
    }
}
/**********************End Of BasicOrderInfo 服务层************************************/


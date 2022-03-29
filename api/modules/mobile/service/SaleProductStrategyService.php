<?php

namespace api\modules\mobile\service;

use api\modules\mobile\models\forms\SaleProductStrategyModel;
use api\modules\mobile\models\forms\SaleProductStrategyQuery;
use api\modules\mobile\models\forms\SaleProductModel;
use fanyou\tools\ArrayHelper;
use fanyou\models\base\SearchModel;
use fanyou\enums\StatusEnum;
use fanyou\tools\DaysTimeHelper;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-05-07
 */
class SaleProductStrategyService
{

/*********************SaleProductStrategy模块服务层************************************/

    /**
     * 获取秒杀活动今日有效时间
     * @param SaleProductModel $model
     * @return array
     */
    public function getTodayEffectTimeById(  $v):array
    {
        $model=new SaleProductModel();
        $model->setAttributes($v,false);
        $array=[];
        $onShow=$model->on_show;
        $start_date=strtotime($model->start_date) ;
        $end_date=strtotime($model->end_date)+86400;
        $start_hour=$model->start_hour;
        $end_hour=$model->end_hour;
        $array['status']=StatusEnum::OUT_STATUS;
        if($onShow===StatusEnum::ENABLED){
            //如果结束时间大于当前时间
            $now=time();
            if($start_date<=$now  && $end_date>$now  ){//活动期间
                $nowHour=date( "H");
                if($start_hour<=$nowHour  &&  $end_hour>$nowHour  ){//已经开始，还未结束
                    $todayStart=DaysTimeHelper::todayStart(true);
                    $array['start_seconds']   =($now-$todayStart)-$start_hour*3600 ;

                    $array['end_seconds']   =$end_hour*3600-($now -$todayStart) ;
                    $array['status'] =StatusEnum::ENABLED;

                }else if($start_hour>$nowHour){  //还未开始
                    $todayStart=DaysTimeHelper::todayStart(true);
                    $array['start_seconds']   =$start_hour*3600-($now-$todayStart) ;
                    $array['status'] =StatusEnum::DISABLED;

                }else if($end_date<$now){    //已结束
                    $array['status'] =StatusEnum::OUT_STATUS;
                }else{
                 //   print_r(   $nowHour);exit;
                }
            }else if($start_date>$now ){ //活动日期还未开始

                    $array['status'] =StatusEnum::DISABLED;
                    $array['start_seconds']   =$start_date +$start_hour*3600-$now ;
            }else if($end_date<$now ){
                    $array['status'] =StatusEnum::OUT_STATUS;
            }
        }
        return  $array;
    }

    /**
     * 根据Id获取品
     * @param $id
     * @return Object
     */
    public function getSaleProductById($id):?SaleProductModel
    {
        return SaleProductModel::findOne($id);
    }

    /**
	 * 添加一条商品活动策略
	 * @param SaleProductStrategyModel $model
     * @return mixed
     * @throws \Throwable
     */
	public function addSaleProductStrategy(SaleProductStrategyModel $model)
	{
		$model->insert();
		return $model->getPrimaryKey();
	}
	/**
	 * 分页获取列表
	 * @param SaleProductStrategyQuery $query
	 * @return array
	 * @throws \yii\web\NotFoundHttpException
	 */
	public function getSaleProductStrategyPage(SaleProductStrategyQuery $query): array
	{
		$searchModel = new SearchModel([
			'model' => SaleProductStrategyModel::class,
			'scenario' => 'default',
			'partialMatchAttributes' => ['name'], // 模糊查询
			'defaultOrder' => [
			'created_at' => SORT_DESC
			],
			'pageSize' => $query->limit,
		]);
		$searchWord = $searchModel->search(array_filter($query->toArray()));
		$result['list'] = ArrayHelper::toArray($searchWord->getModels());
		$result['totalCount'] = $searchWord->pagination->totalCount;
		return $result;
	}

	/**
	 * 根据Id获取动策略
	 * @param $id
	 * @return Object
	 */
	public function getOneById($id):?SaleProductStrategyModel
	{
		return SaleProductStrategyModel::findOne($id);
	}

	/**
	 * 根据Id更新动策略
	 * @param SaleProductStrategyModel $model
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function updateSaleProductStrategyById (SaleProductStrategyModel $model): int
	{
		$model->setOldAttribute('id',$model->id);
		return $model->update();
	}
	/**
	 * 根据Id删除动策略
	 * @param $id
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function deleteById ($id) : int
	{
		$model = SaleProductStrategyModel::findOne($id);
		return  $model->delete();
	}
}
/**********************End Of SaleProductStrategy 服务层************************************/


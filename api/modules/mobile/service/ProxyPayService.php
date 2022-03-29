<?php

namespace api\modules\mobile\service;

use api\modules\mobile\models\forms\ProxyPayModel;
use api\modules\mobile\models\forms\ProxyPayQuery;
use fanyou\enums\QueryEnum;
use fanyou\tools\ArrayHelper;
use fanyou\models\base\SearchModel;

/**
 * Class ProxyPayService
 * @package api\modules\seller\service
 * @author: HenryJia
 * @E-mail: 316560925@qq.com
 * @date: 2020-10-09
 */
class ProxyPayService
{

/*********************ProxyPay模块服务层************************************/
	/**
	 * 添加一条找人代付ProxyPaddProxyPayayModel
	 * @param ProxyPayModel $model
     * @return mixed
     * @throws \Throwable
     */
	public function addProxyPay(ProxyPayModel $model)
	{
		$model->insert();
		return $model->getPrimaryKey();
	}
	/**
	 * 分页获取列表
	 * @param ProxyPayQuery $query
	 * @return array
	 * @throws \yii\web\NotFoundHttpException
	 */
	public function getProxyPayPage(ProxyPayQuery $query): array
	{
		$searchModel = new SearchModel([
			'model' => ProxyPayModel::class,
			'scenario' => 'default',
			'partialMatchAttributes' => ['name'], // 模糊查询
			'defaultOrder' => $query->getOrdersArray(),
			'pageSize' => $query->limit,
		]);
		$searchWord = $searchModel->search( $query->toArray([],[QueryEnum::LIMIT]) );
		$result['list'] = ArrayHelper::toArray($searchWord->getModels());
		$result['totalCount'] = $searchWord->pagination->totalCount;
		return $result;
	}

    /**
     * 根据订单Id和用户小程序Id查找支付
     * @param $orderId
     * @param $openId
     * @return ProxyPayModel|null
     */
    public function getByOrderIdAndMiniAppId($orderId,$openId)
    {
        return ProxyPayModel::findOne(['order_id'=>$orderId,'mini_app_id'=>$openId]);
       // return ProxyPayModel::find()->where(['order_id'=>$orderId,'mini_app_id'=>$openId])->orderBy(['created_at'=>SORT_DESC])->one();
    }
	/**
	 * 根据Id获取找人代付
	 * @param $id
	 * @return Object
	 */
	public function getOneById($id)
	{
		return ProxyPayModel::findOne($id);
	}

	/**
	 * 根据Id更新找人代付
	 * @param ProxyPayModel $model
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function updateProxyPayById (ProxyPayModel $model): int
	{
		$model->setOldAttribute('id',$model->id);
		return $model->update();
	}
	/**
	 * 根据Id删除找人代付
	 * @param $id
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function deleteById ($id) : int
	{
		$model = ProxyPayModel::findOne($id);
		return  $model->delete();
	}
}
/**********************End Of ProxyPay 服务层************************************/


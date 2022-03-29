<?php

namespace api\modules\seller\service;

use api\modules\mobile\models\forms\UserVipDetailModel;
use api\modules\seller\models\forms\UserVipDetailQuery;
use api\modules\seller\models\forms\UserVipPayModel;
use api\modules\seller\models\forms\UserVipPayQuery;
use fanyou\enums\QueryEnum;
use fanyou\enums\StatusEnum;
use fanyou\tools\ArrayHelper;
use fanyou\models\base\SearchModel;

/**
 * Class UserVipPayService
 * @package api\modules\seller\service
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-06-16 15:00
 */
class UserVipPayService
{

/*********************UserVipPay模块服务层************************************/
	/**
	 * 添加一条购买会员
	 * @param UserVipPayModel $model
     * @return mixed
     * @throws \Throwable
     */
	public function addUserVipPay(UserVipPayModel $model)
	{
		$model->insert();
		return $model->getPrimaryKey();
	}
	/**
	 * 分页获取列表
	 * @param UserVipPayQuery $query
	 * @return array
	 * @throws \yii\web\NotFoundHttpException
	 */
	public function getUserVipPayPage(UserVipPayQuery $query): array
	{
		$searchModel = new SearchModel([
			'model' => UserVipPayModel::class,
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
    public function getVipDetailPage(UserVipDetailQuery $query): array
    {
        $searchModel = new SearchModel([
            'model' => UserVipDetailModel::class,
            'scenario' => 'default',
            'partialMatchAttributes' => ['name'], // 模糊查询
            'defaultOrder' => $query->getOrdersArray(),
            'pageSize' => $query->limit,
        ]);
        $searchWord = $searchModel->search( $query->toArray([],[QueryEnum::LIMIT]) );
        $result['list'] = ArrayHelper::toArray($searchWord->getModels());
        $result['totalCount'] = $searchWord->pagination->totalCount;
        $result['sumAmount'] = $this->sumVipDetail($query)['amount'];
        return $result;
    }
    public function sumVipDetail(UserVipDetailQuery $query): array
    {
        $searchModel = new SearchModel([
            'model' => UserVipDetailModel::class,
            'scenario' => 'default',
            'select'=>['amount'=>'SUM(amount)'],
            'partialMatchAttributes' => ['name'], // 模糊查询
            'defaultOrder' => $query->getOrdersArray(),
        ]);
        $searchWord = $searchModel->search( $query->toArray() );
        return ArrayHelper::toArray($searchWord->query->one());
    }
    /**
     * 获取一条终身会员资格
     * @param UserVipPayQuery $query
     * @return UserVipPayModel|null
     */
    public function getPermanentOne(UserVipPayQuery $query ):?UserVipPayModel
    {
        return UserVipPayModel::find()->where(['is_permanent'=>StatusEnum::ENABLED,'status'=>StatusEnum::ENABLED,'merchant_id'=>$query->merchant_id])->one();
    }
	/**
	 * 根据Id获取员
	 * @param $id
	 * @return Object
	 */
	public function getOneById($id)
	{
		return UserVipPayModel::findOne($id);
	}

	/**
	 * 根据Id更新员
	 * @param UserVipPayModel $model
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function updateUserVipPayById (UserVipPayModel $model): int
	{
		$model->setOldAttribute('id',$model->id);
		return $model->update();
	}
	/**
	 * 根据Id删除员
	 * @param $id
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function deleteById ($id) : int
	{
		$model = UserVipPayModel::findOne($id);
		return  $model->delete();
	}
}
/**********************End Of UserVipPay 服务层************************************/


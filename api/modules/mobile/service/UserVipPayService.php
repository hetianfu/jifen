<?php

namespace api\modules\mobile\service;

use api\modules\mobile\models\forms\UserVipPayModel;
use api\modules\mobile\models\forms\UserVipPayQuery;
use fanyou\enums\AppEnum;
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

    /**
     * 获取一条终身会员资格
     * @return UserVipPayModel|null
     */
    public function getPermanentOne():?UserVipPayModel
    {
        return UserVipPayModel::find()->where(['is_permanent'=>StatusEnum::ENABLED,'is_vip'=>StatusEnum::ENABLED,
            'merchant_id'=>AppEnum::MERCHANTID])->one();
    }

    /**
     * 统计充值会员数量
     * @param bool $isAll
     * @return int|string
     */
    public function count($isAll=false)
    {
        if($isAll){
          return  UserVipPayModel::find()->count();
        }else{
            return UserVipPayModel::find()->where(['is_vip'=>StatusEnum::ENABLED])->count();
        }

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


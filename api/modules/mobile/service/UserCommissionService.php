<?php

namespace api\modules\mobile\service;

use api\modules\mobile\models\forms\UserCommissionModel;
use api\modules\mobile\models\forms\UserCommissionQuery;
use fanyou\enums\QueryEnum;
use fanyou\tools\ArrayHelper;
use fanyou\models\base\SearchModel;

/**
 * Class UserCommissionService
 * @package api\modules\mobile\service
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-06-04 17:15
 */
class UserCommissionService
{
    private $infoService;
    public function __construct()
    {
        $this->infoService = new UserInfoService();
    }
/*********************UserCommission模块服务层************************************/
	/**
	 * 添加一条用户佣金
	 * @param UserCommissionModel $model
     * @return mixed
     * @throws \Throwable
     */
	public function addUserCommission(UserCommissionModel $model)
	{
		$model->insert();
		return $model->getPrimaryKey();
	}
	/**
	 * 分页获取列表
	 * @param UserCommissionQuery $query
	 * @return array
	 * @throws \yii\web\NotFoundHttpException
	 */
	public function getUserCommissionPage(UserCommissionQuery $query): array
	{
		$searchModel = new SearchModel([
			'model' => UserCommissionModel::class,
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
	 * 根据Id获取金
	 * @param $id
	 * @return UserCommissionModel
	 */
	public function getOneById($id): UserCommissionModel
	{
	    $model=UserCommissionModel::findOne($id);
        $model= empty($model)?new UserCommissionModel():$model;
        $model->sons=$this->infoService->countDisciples($id);
		return $model ;
	}

	/**
	 * 根据Id更新金
	 * @param UserCommissionModel $model
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function updateUserCommissionById (UserCommissionModel $model): int
	{
		$model->setOldAttribute('id',$model->id);
		return $model->update();
	}
	/**
	 * 根据Id删除金
	 * @param $id
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function deleteById ($id) : int
	{
		$model = UserCommissionModel::findOne($id);
		return  $model->delete();
	}
}
/**********************End Of UserCommission 服务层************************************/


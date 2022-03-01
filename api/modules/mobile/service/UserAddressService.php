<?php

namespace api\modules\mobile\service;

use api\modules\mobile\models\forms\UserAddressModel;
use api\modules\mobile\models\forms\UserAddressQuery;
use fanyou\enums\StatusEnum;
use fanyou\tools\ArrayHelper;
use fanyou\models\base\SearchModel;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-03-23
 */
class UserAddressService
{

/*********************UserAddress模块服务层************************************/
	/**
	 * 添加一条收货地址
	 * @param UserAddressModel $model
     * @return mixed
     * @throws \Throwable
     */
	public function addUserAddress(UserAddressModel $model)
	{
		$model->insert();
		return $model->getPrimaryKey();
	}
	/**
	 * 分页获取列表
	 * @param UserAddressQuery $query
	 * @return array
	 * @throws \yii\web\NotFoundHttpException
	 */
	public function getUserAddressPage(UserAddressQuery $query): array
	{
		$searchModel = new SearchModel([
			'model' => UserAddressModel::class,
			'scenario' => 'default',
			//'partialMatchAttributes' => ['name'], // 模糊查询
			'defaultOrder' => $query->getOrdersArray(),
			'pageSize' => $query->limit,
		]);
		$searchWord = $searchModel->search( $query->toArray([],['limit']) );
		$result['list'] = ArrayHelper::toArray($searchWord->getModels());
		$result['totalCount'] = $searchWord->pagination->totalCount;
		return $result;
	}
    public function getUserAddressList(UserAddressQuery $query): array
    {
        $searchModel = new SearchModel([
            'model' => UserAddressModel::class,
            'scenario' => 'default',
          //  'partialMatchAttributes' => ['name'], // 模糊查询
            'defaultOrder' =>$query->getOrdersArray(),
        ]);
        $searchWord = $searchModel->search( $query->toArray() );
        return ArrayHelper::toArray($searchWord->getModels());;
    }
	/**
	 * 根据Id获取收货地址
	 * @param $id
	 * @return Object
	 */
	public function getUserAddressById($id)
	{
		return UserAddressModel::findOne($id);
	}

	/**
	 * 根据Id更新收货地址
	 * @param UserAddressModel $model
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function updateUserAddressById (UserAddressModel $model): int
	{
		$model->setOldAttribute('id',$model->id);
		return $model->update();
	}


    public function setAsDefaultAddress (UserAddressModel $model): int
    {
        $result=StatusEnum::DISABLED;
        $userId=$model->user_id;
        if(empty($userId)){
            return $result;
        }
        UserAddressModel::updateAll(['is_default'=>StatusEnum::DISABLED],['user_id'=>$userId ,'is_default'=>StatusEnum::ENABLED] ) ;
        $model->setOldAttribute('id',$model->id);
        $model->is_default=StatusEnum::ENABLED;
        $result=$model->update();
        return $result;
    }
	/**
	 * 根据Id删除收货地址
	 * @param $id
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function deleteById ($id) : int
	{
		$model = UserAddressModel::findOne($id);
		return  $model->delete();
	}
}
/**********************End Of UserAddress 服务层************************************/


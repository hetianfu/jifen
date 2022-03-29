<?php

namespace api\modules\seller\service;

use api\modules\seller\models\forms\DistributeUserModel;
use api\modules\seller\models\forms\DistributeUserQuery;
use api\modules\seller\models\forms\UserInfoModel;
use fanyou\enums\QueryEnum;
use fanyou\enums\StatusEnum;
use fanyou\tools\ArrayHelper;
use fanyou\models\base\SearchModel;

/**
 * Class DistributeUserService
 * @package api\modules\seller\service
 * @author: HenryJia
 * @E-mail: 316560925@qq.com
 * @date: 2020-09-01
 */
class DistributeUserService
{

/*********************DistributeUser模块服务层************************************/
	/**
	 * 添加一条用户分销配置
	 * @param UserInfoModel $model
     * @return mixed
     * @throws \Throwable
     */
	public function addDistributeUser(UserInfoModel $model)
	{

        $model->setOldAttribute('id',$model->id);
        return $model->update();
	}
	/**
	 * 分页获取列表
	 * @param DistributeUserQuery $query
	 * @return array
	 * @throws \yii\web\NotFoundHttpException
	 */
	public function getDistributeUserPage(DistributeUserQuery $query): array
	{
        if(!isset($query->identify)){
	    $query->identify=QueryEnum::GE.StatusEnum::S_SUCCESS;
        }
        $searchModel = new SearchModel([
            'model' => UserInfoModel::class,
            'scenario' => 'default',
            'partialMatchAttributes' => ['telephone'], // 模糊查询
            'select'=>['id','identify','head_img','nick_name','telephone','status','city','code'],
            'defaultOrder' => $query->getOrdersArray(),
            'pageSize' => $query->limit,
        ]);
        $searchWord = $searchModel->search( $query->toArray([],[QueryEnum::LIMIT]) );

        $result['list'] = ArrayHelper::toArray($searchWord->getModels());
        $result['totalCount'] = $searchWord->pagination->totalCount;
        return $result;
	}

	/**
	 * 根据Id获取用户分销配置
	 * @param $id
	 * @return Object
	 */
	public function getOneById($id)
	{
		return DistributeUserModel::findOne($id);
	}

	/**
	 * 根据Id更新用户分销配置
	 * @param DistributeUserModel $model
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function updateDistributeUserById (DistributeUserModel $model): int
	{
		$model->setOldAttribute('id',$model->id);
		return $model->update();
	}
	/**
	 * 根据Id删除用户分销配置
	 * @param $id
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function deleteById ($id) : int
	{
		$model = DistributeUserModel::findOne($id);
		return  $model->delete();
	}
}
/**********************End Of DistributeUser 服务层************************************/


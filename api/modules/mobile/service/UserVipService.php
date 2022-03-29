<?php

namespace api\modules\mobile\service;

use api\modules\mobile\models\forms\UserVipModel;
use api\modules\mobile\models\forms\UserVipQuery;
use fanyou\enums\QueryEnum;
use fanyou\enums\StatusEnum;
use fanyou\tools\ArrayHelper;
use fanyou\models\base\SearchModel;

/**
 * Class UserVipService
 * @package api\modules\seller\service
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-06-16 15:00
 */
class UserVipService
{

/*********************UserVip模块服务层************************************/
	/**
	 * 添加一条用户VIP会员
	 * @param UserVipModel $model
     * @return mixed
     * @throws \Throwable
     */
	public function addUserVip(UserVipModel $model)
	{
		$model->insert();
		return $model->getPrimaryKey();
	}
	/**
	 * 分页获取列表
	 * @param UserVipQuery $query
	 * @return array
	 * @throws \yii\web\NotFoundHttpException
	 */
	public function getUserVipPage(UserVipQuery $query): array
	{
		$searchModel = new SearchModel([
			'model' => UserVipModel::class,
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
	 * 根据Id获取IP会员
	 * @param $id
	 * @return UserVipModel
	 */
	public function getOneById($id):?UserVipModel
	{
		return UserVipModel::findOne($id);
	}

    /**
     * 是否VIp
     * @param $id
     * @return bool
     */
    public function isVip($id):bool
    {
        $model=$this->getOneById($id);
        if(!empty($model) && !empty($model->is_vip)){
            if($model->is_permanent){
                return true;
            }else{
                if($model->overdue_at >time()){
                    return true;
                }else{
                    UserVipModel::updateAll(['is_vip'=>StatusEnum::DISABLED],['id'=>$id]);
                }
            }
        }
        return false;
    }
	/**
	 * 根据Id更新IP会员
	 * @param UserVipModel $model
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function updateUserVipById (UserVipModel $model): int
	{
		$model->setOldAttribute('id',$model->id);
		return $model->update();
	}
	/**
	 * 根据Id删除IP会员
	 * @param $id
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function deleteById ($id) : int
	{
		$model = UserVipModel::findOne($id);
		return  $model->delete();
	}
}
/**********************End Of UserVip 服务层************************************/


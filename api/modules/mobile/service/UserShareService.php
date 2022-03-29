<?php

namespace api\modules\mobile\service;

use api\modules\mobile\models\forms\UserShareModel;
use api\modules\mobile\models\forms\UserShareQuery;
use fanyou\enums\QueryEnum;
use fanyou\tools\ArrayHelper;
use fanyou\models\base\SearchModel;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-05-30
 */
class UserShareService
{

/*********************UserShare模块服务层************************************/
	/**
	 * 添加一条用户分享码
	 * @param UserShareModel $model
     * @return mixed
     * @throws \Throwable
     */
	public function addUserShare(UserShareModel $model)
	{
	    if(!empty($this->getOneById($model->id))){
            $model->setOldAttribute('id',$model->id);
            $model->update();
	        return $model->id;
        }
		$model->insert();
		return $model->getPrimaryKey();
	}
	/**
	 * 分页获取列表
	 * @param UserShareQuery $query
	 * @return array
	 * @throws \yii\web\NotFoundHttpException
	 */
	public function getUserSharePage(UserShareQuery $query): array
	{
		$searchModel = new SearchModel([
			'model' => UserShareModel::class,
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
	 * 根据Id获取享码
	 * @param $id
	 * @return Object
	 */
	public function getOneById($id)
	{
		return UserShareModel::findOne($id);
	}

    /**
     * 获取一条
     * @param $userId
     * @param $keyId
     * @param $type
     * @return UserShareModel|null
     */
    public function getOne($userId,$keyId,$type)
    {
        return UserShareModel::findOne(['user_id'=>$userId,'key_id'=>$keyId,'key_type'=>$type]);
    }

    /**
     * 获取分享图片地址
     * @param $id
     * @return Object
     */
    public function getShareUrl($id)
    {
        $model=UserShareModel::findOne($id);
        if(empty($model)){
            return null;
        }
        return $model->share_url;
    }
	/**
	 * 根据Id更新享码
	 * @param UserShareModel $model
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function updateUserShareById (UserShareModel $model): int
	{
		$model->setOldAttribute('id',$model->id);
		return $model->update();
	}
	/**
	 * 根据Id删除享码
	 * @param $id
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function deleteById ($id) : int
	{
		$model = UserShareModel::findOne($id);
		return  $model->delete();
	}
}
/**********************End Of UserShare 服务层************************************/


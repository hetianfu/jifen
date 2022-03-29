<?php

namespace api\modules\seller\service;

use api\modules\seller\models\forms\UserInfoModel;
use api\modules\seller\models\forms\UserScoreDetailModel;
use api\modules\seller\models\forms\UserScoreDetailQuery;
use fanyou\enums\entity\ScoreTypeEnum;
use fanyou\enums\QueryEnum;
use fanyou\enums\StatusEnum;
use fanyou\tools\ArrayHelper;
use fanyou\models\base\SearchModel;
use UserModel;
use yii\db\Expression;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-04-27
 */
class UserScoreDetailService
{

/*********************UserScoreDetail模块服务层************************************/
	/**
	 * 添加一条用户积分详情
	 * @param UserScoreDetailModel $model
     * @return mixed
     * @throws \Throwable
     */
	public function addUserScoreDetail(UserScoreDetailModel $model)
	{
        $userInfo=UserInfoModel::findOne($model->user_id);

        $model->score=$model->is_deduct* $model->score;

        //用户的积分小于0，
        if( empty( $userInfo->total_score) ){
            //如果是抵扣
            if(  empty($model->score) ){
                return StatusEnum::DISABLED;
            }
        }  else if( ($userInfo->total_score +$model->score )<0)  {
            return StatusEnum::DISABLED;
        }

        $model->last_score=$userInfo->total_score;
        $this->deductUserScore($model->user_id,$model->score);

        $model->insert();
        return $model->getPrimaryKey();

	}
	/**
	 * 分页获取列表
	 * @param UserScoreDetailQuery $query
	 * @return array
	 * @throws \yii\web\NotFoundHttpException
	 */
	public function getUserScoreDetailPage(UserScoreDetailQuery $query): array
	{
		$searchModel = new SearchModel([
			'model' => UserScoreDetailModel::class,
			'scenario' => 'default',
			'partialMatchAttributes' => ['name'], // 模糊查询
			'defaultOrder' => [
			'created_at' => SORT_DESC
			],
			'pageSize' => $query->limit,
		]);
		$searchWord = $searchModel->search($query->toArray([],[QueryEnum::LIMIT]));
		$result['list'] = ArrayHelper::toArray($searchWord->getModels());
		$result['totalCount'] = $searchWord->pagination->totalCount;
		return $result;
	}

    /**
     * 统计个数
     * @param UserScoreDetailQuery $query
     * @return mixed
     * @throws \yii\web\NotFoundHttpException
     */
    public function count(UserScoreDetailQuery $query)
    {
        $searchModel = new SearchModel([
        'model' => UserScoreDetailModel::class,
        'scenario' => 'default',
        'partialMatchAttributes' => ['name'], // 模糊查询
        'defaultOrder' => [
            'created_at' => SORT_DESC
        ],
        ]);
        $searchWord = $searchModel->search($query->toArray());
        return  $searchWord->query->count();
    }
    /**
     * 统计数量
     * @param UserScoreDetailQuery $query
     * @return mixed
     * @throws \yii\web\NotFoundHttpException
     */
    public function sum(UserScoreDetailQuery $query)
    {
        $searchModel = new SearchModel([
            'model' => UserScoreDetailModel::class,
            'scenario' => 'default',
            'partialMatchAttributes' => ['name'], // 模糊查询
            'defaultOrder' => [
                'created_at' => SORT_DESC
            ],
            'select'=>['score'=>'SUM(score)']
        ]);
        $searchWord = $searchModel->search($query->toArray());
        return  $searchWord->query->one()['score'];
    }
	/**
	 * 根据Id获取分详情
	 * @param $id
	 * @return Object
	 */
	public function getOneById($id)
	{
		return UserScoreDetailModel::findOne($id);
	}

	/**
	 * 根据Id更新分详情
	 * @param UserScoreDetailModel $model
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function updateUserScoreDetailById (UserScoreDetailModel $model): int
	{
		$model->setOldAttribute('id',$model->id);
		return $model->update();
	}
	/**
	 * 根据Id删除分详情
	 * @param $id
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function deleteById ($id) : int
	{
		$model = UserScoreDetailModel::findOne($id);
		return  $model->delete();
	}

    /**
     * 抵扣积分
     * @param $userId
     * @param int $score
     * @return int
     */
    public function  deductUserScore($userId,$score=0){

        return UserInfoModel::updateAll(['total_score' => new Expression('`total_score` + '.$score)], ['id'=>$userId]);

    }
}
/**********************End Of UserScoreDetail 服务层************************************/


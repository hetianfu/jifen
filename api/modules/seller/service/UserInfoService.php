<?php

namespace api\modules\seller\service;

use api\modules\mobile\models\forms\UserCommissionDetailModel;
use api\modules\seller\models\forms\UserInfoModel;
use api\modules\seller\models\forms\UserInfoQuery;
use api\modules\seller\models\forms\UserLevelModel;
use api\modules\seller\models\forms\UserLevelQuery;
use fanyou\enums\QueryEnum;
use fanyou\enums\WalletStatusEnum;
use fanyou\models\base\SearchModel;
use fanyou\tools\ArrayHelper;
use yii\db\Expression;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-04-23
 */
class UserInfoService
{

/*********************UserInfo模块服务层************************************/
	/**
	 * 添加一条用户信息
	 * @param UserInfoModel $model
     * @return mixed
     * @throws \Throwable
     */
	public function addUserInfo(UserInfoModel $model)
	{
		$model->insert();
		return $model->getPrimaryKey();
	}
	/**
	 * 分页获取列表
	 * @param UserInfoQuery $query
	 * @return array
	 * @throws \yii\web\NotFoundHttpException
	 */
	public function getUserInfoPage(UserInfoQuery $query): array
	{

		$searchModel = new SearchModel([
			'model' => UserInfoModel::class,
			'scenario' => 'default',
			'partialMatchAttributes' => ['nick_name','telephone','last_log_in_ip'], // 模糊查询
			'defaultOrder' => $query->getOrdersArray() ,
			'pageSize' => $query->limit,
		]);
		$searchWord = $searchModel->search( $query->toArray([],[QueryEnum::LIMIT]) );
		$result['list'] = ArrayHelper::toArray($searchWord->getModels());
		$result['totalCount'] = $searchWord->pagination->totalCount;
		return $result;
	}

    /**
     * 用于搜索用户
     * @param UserInfoQuery $query
     * @return array
     * @throws \yii\web\NotFoundHttpException
     */
    public function searchUserInfoPage(UserInfoQuery $query): array
    {
        $searchModel = new SearchModel([
            'model' => UserInfoModel::class,
            'scenario' => 'default',
            'partialMatchAttributes' => ['nick_name','telephone'], // 模糊查询
            'defaultOrder' =>$query->getOrdersArray(),
            'select'=>['id','telephone','nick_name','code','head_img'],
            'pageSize' => $query->limit,
        ]);
        $searchWord = $searchModel->search( $query->toArray([],[QueryEnum::LIMIT]) );
        $result['list'] = ArrayHelper::toArray($searchWord->getModels());
        $result['totalCount'] = $searchWord->pagination->totalCount;
        return $result;
    }


    /**
     * 根据日期统计
     * @param UserInfoQuery $query
     * @return array
     * @throws \yii\web\NotFoundHttpException
     */
    public function statisticByDate(UserInfoQuery $query): array
    {
        $select=['id'=>'count(1)','date'=>'FROM_UNIXTIME(  created_at , \'%Y-%m-%d\' )'  ];
        $searchArray= [
            'model' => UserInfoModel::class,
            'scenario' => 'default',
            'partialMatchAttributes' => ['nick_name','telephone'], // 模糊查询
            'defaultOrder' => [
                'created_at' => SORT_DESC
            ],
            'groupBy'=>['FROM_UNIXTIME( created_at ,\'%Y-%m-%d\')']
        ];
        $searchArray['select']=$select;
        $searchModel = new SearchModel($searchArray  );

        $searchWord = $searchModel->search( $query->toArray() );

        return   ArrayHelper::toArray($searchWord->getModels());
    }


    /**
     * 统计用户数量
     * @param UserInfoQuery $query
     * @return mixed
     * @throws \yii\web\NotFoundHttpException
     */
    public function count(UserInfoQuery $query)
    {
        $searchModel = new SearchModel([
            'model' => UserInfoModel::class,
            'scenario' => 'default',
        ]);
        $searchWord = $searchModel->search( $query->toArray());
        return  $searchWord->query->count();
    }

    /**
     * 统计总积分
     * @param UserInfoQuery $query
     * @return mixed
     * @throws \yii\web\NotFoundHttpException
     */
    public function sumScore(UserInfoQuery $query)
    {
        $searchModel = new SearchModel([
            'model' => UserInfoModel::class,
            'scenario' => 'default',
            'select'=>['total_score'=>'SUM(total_score)']
        ]);
        $searchWord = $searchModel->search( $query->toArray( ) );

        return  $searchWord->query->one()['total_score'];
    }
    /**
     * 统计钱包余额
     * @param UserInfoQuery $query
     * @return mixed
     * @throws \yii\web\NotFoundHttpException
     */
    public function sumWallet(UserInfoQuery $query)
    {
        $searchModel = new SearchModel([
            'model' => UserInfoModel::class,
            'scenario' => 'default',
            'select'=>['amount'=>'SUM(amount)']
        ]);
        $searchWord = $searchModel->search( $query->toArray( ) );
        return  $searchWord->query->one()['amount'];
    }
	/**
	 * 根据Id获取息
	 * @param $id
	 * @return Object
	 */
	public function getOneById($id)
	{
		return UserInfoModel::findOne($id);
	}
    public function getOneByOpenId($openId)
    {
        return UserInfoModel::findOne(['open_id'=>$openId]);
    }
    public function getOneByUnionId($unionId)
    {
        return UserInfoModel::findOne(['union_id'=>$unionId]);
    }

	/**
	 * 根据Id更新息
	 * @param UserInfoModel $model
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function updateUserInfoById (UserInfoModel $model): int
	{
		$model->setOldAttribute('id',$model->id);
		return $model->update();
	}
	/**
	 * 根据Id删除息
	 * @param $id
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function deleteById ($id) : int
	{
		$model = UserInfoModel::findOne($id);
		return  $model->delete();
	}

    /**
     * 获取徒弟列表
     * @param $query
     * @return mixed
     * @throws \yii\web\NotFoundHttpException
     */
    public function getDisciplePage(UserInfoQuery $query)
    {
        $searchModel = new SearchModel([
            'model' => UserInfoModel::class,
            'scenario' => 'default',
            'partialMatchAttributes' => ['name'], // 模糊查询
            'defaultOrder' => [
                'created_at' => SORT_DESC
            ],
            'select' => ['id', 'nick_name', 'head_img', 'created_at'],
            'pageSize' => $query->limit,
        ]);
        $searchWord = $searchModel->search($query->toArray([], [QueryEnum::LIMIT]));
        $list = ArrayHelper::toArray($searchWord->getModels());
        foreach ($list as $k => $v) {
            $list[$k]['contribution'] = UserCommissionDetailModel::find()->select(['SUM(amount) as amount'])
                ->where(['user_id' => $query->parent_id, 'provider_id' => $v['id']])

                ->andWhere(['in','type',[ WalletStatusEnum::CONSUME,WalletStatusEnum::REFUND,WalletStatusEnum::TEAM,WalletStatusEnum::PROXY,WalletStatusEnum::DISTRIBUTE]])
                ->one()['amount'];//  20;//$this->commissionService->count();
        }
        $result['list'] = $list;
        $result['totalCount'] = $searchWord->pagination->totalCount;
        return $result;

    }
    public function getDiscipleList($parentId)
    {
        $array=UserInfoModel::find()->select(['id','head_img','nick_name','identify','telephone','status','code','city'])->where(['parent_id'=>$parentId])->all();
        return ArrayHelper::toArray($array);

    }
    /**
     * 统计下家
     * @param $parentId
     * @return int|string
     */
    public function countDisciples($parentId)
    {
        return UserInfoModel::find()->where(['parent_id' => $parentId])->count();
    }

    /********************用户等级模块服务层************************************/
    /**
     * 添加一条用户等级
     * @param UserLevelModel $model
     * @return mixed
     * @throws \Throwable
     */
    public function addUserLevel(UserLevelModel $model)
    {
        $model->insert();
        return $model->getPrimaryKey();
    }
    /**
     * 分页获取列表
     * @param UserLevelQuery $query
     * @return array
     * @throws \yii\web\NotFoundHttpException
     */
    public function getUserLevelPage(UserLevelQuery $query): array
    {
        $searchModel = new SearchModel([
            'model' => UserLevelModel::class,
            'scenario' => 'default',
            'partialMatchAttributes' => ['name'], // 模糊查询
            'defaultOrder' => [
                'id' => SORT_ASC
            ],
        ]);
        $searchWord = $searchModel->search( ($query->toArray([],[QueryEnum::LIMIT])));
        $result['list'] = ArrayHelper::toArray($searchWord->getModels());
        $result['totalCount'] = $searchWord->pagination->totalCount;
        return $result;
    }
    public function getUserLevelList(UserLevelQuery $query): array
    {
        $searchModel = new SearchModel([
            'model' => UserLevelModel::class,
            'scenario' => 'default',
            'partialMatchAttributes' => ['name'], // 模糊查询
            'defaultOrder' => [
                'id' => SORT_ASC
            ],
        ]);
        $searchWord = $searchModel->search( ($query->toArray()));
        return  ArrayHelper::toArray($searchWord->getModels());
    }
    /**
     * 根据Id获取级
     * @param $id
     * @return Object
     */
    public function getUserLevelById($id)
    {
        return UserLevelModel::findOne($id);
    }

    /**
     * 根据Id更新级
     * @param UserLevelModel $model
     * @return int
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function updateUserLevelById (UserLevelModel $model): int
    {
        $model->setOldAttribute('id',$model->id);
        return $model->update();
    }
    /**
     * 根据Id删除级
     * @param $id
     * @return int
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function deleteUserLevelById ($id) : int
    {
        $model = UserLevelModel::findOne($id);
        return  $model->delete();
    }


    /*********************UserScoreDetail模块服务层************************************/
//    /**
//     * 添加一条用户积分详情
//     * @param UserScoreDetailModel $model
//     * @return mixed
//     * @throws \Throwable
//     */
//    public function addUserScoreDetail(UserScoreDetailModel $model)
//    {
//        $model->insert();
//        return $model->getPrimaryKey();
//    }
//    /**
//     * 分页获取列表
//     * @param UserScoreDetailQuery $query
//     * @return array
//     * @throws \yii\web\NotFoundHttpException
//     */
//    public function getUserScoreDetailPage(UserScoreDetailQuery $query): array
//    {
//        $searchModel = new SearchModel([
//            'model' => UserScoreDetailModel::class,
//            'scenario' => 'default',
//            'partialMatchAttributes' => ['name'], // 模糊查询
//            'defaultOrder' => [
//                'created_at' => SORT_DESC
//            ],
//            'pageSize' => $query->limit,
//        ]);
//        $searchWord = $searchModel->search(array_filter($query->toArray()));
//        $result['list'] = ArrayHelper::toArray($searchWord->getModels());
//        $result['totalCount'] = $searchWord->pagination->totalCount;
//        return $result;
//    }
//
//    /**
//     * 根据Id获取分详情
//     * @param $id
//     * @return Object
//     */
//    public function getScoreDetailById($id)
//    {
//        return UserScoreDetailModel::findOne($id);
//    }
//
//    /**
//     * 根据Id更新分详情
//     * @param UserScoreDetailModel $model
//     * @return int
//     * @throws \Throwable
//     * @throws \yii\db\StaleObjectException
//     */
//    public function updateUserScoreDetailById (UserScoreDetailModel $model): int
//    {
//        $model->setOldAttribute('id',$model->id);
//        return $model->update();
//    }
//    /**
//     * 根据Id删除分详情
//     * @param $id
//     * @return int
//     * @throws \Throwable
//     * @throws \yii\db\StaleObjectException
//     */
//    public function deleteScoreDetailById ($id) : int
//    {
//        $model = UserScoreDetailModel::findOne($id);
//        return  $model->delete();
//    }

    /**
     * 用户充值--提现充值
     * @param $userId
     * @param $amount
     */
    public function chargeUserWallet($userId,$amount){

      UserInfoModel::updateAll(['amount' => new Expression('`amount` + ' . $amount),'charge_amount' => new Expression('`charge_amount` + ' . $amount)]
            ,['id'=>$userId] );
    }



}
/**********************End Of UserInfo 服务层************************************/


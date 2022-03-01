<?php

namespace api\modules\mobile\service;

use api\modules\mobile\models\forms\UserCodeModel;
use api\modules\mobile\models\forms\UserCommissionDetailModel;
use api\modules\mobile\models\forms\UserInfoModel;
use api\modules\mobile\models\forms\UserInfoQuery;
use api\modules\mobile\models\forms\UserScoreDetailModel;
use api\modules\mobile\models\forms\UserScoreDetailQuery;
use api\modules\mobile\models\forms\UserVipModel;
use api\modules\mobile\models\forms\UserVipPayModel;
use fanyou\enums\HttpErrorEnum;
use fanyou\enums\QueryEnum;
use fanyou\enums\StatusEnum;
use fanyou\enums\WalletStatusEnum;
use fanyou\error\errorCode\ErrorUser;
use fanyou\error\FanYouHttpException;
use fanyou\models\base\SearchModel;
use fanyou\tools\ArrayHelper;
use yii\db\Expression;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-04-23
 */
class UserInfoService extends BasicService
{
    private $codeService;
    private $vipService;

    public function __construct()
    {
        $this->codeService = new UserCodeService();

        $this->vipService = new UserVipService();

    }

    /*********************UserInfo模块服务层************************************/
    /**
     * @param $score
     * @param $userId
     * @return int
     */
    public function lockScore($score, $userId)
    {
        //锁定积分
        return UserInfoModel::updateAll(['lock_score' => new Expression('`lock_score` + ' . $score)], ['id' => $userId]);
    }

    /**
     * 添加一条用户信息
     * @param UserInfoModel $model
     * @return mixed
     * @throws \Throwable
     */
    public function addUserInfo(UserInfoModel $model)
    {
        if(!empty($model->mini_app_open_id)){
            $info = $this->getOneByMiniAppOpenId($model->mini_app_open_id);
            if (!empty($info)) {
                return $info->id;
            }
        }
        if(empty($model->id)){
        $model->id = parent::getRandomId();
        }
        $code = new UserCodeModel();
        $code->user_id = $model->id;
        $code->status = StatusEnum::ENABLED;
        $model->code = $this->codeService->addUserCode($code);

        $model->insert();
        return $model->getPrimaryKey();
    }

    /**
     * 获取用户钱包余额
     * @param $id
     * @return int
     * @throws FanYouHttpException]
     */
    public function getAmountById($id): int
    {
        $model = $this->getOneById($id);
        if (empty($model)) {
            throw new  FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, ErrorUser::USER_RE_LOGIN);
        }
        return $model->amount;
    }


    /**
     * 根据Id获取息
     * @param $id
     * @return UserInfoModel
     */
    public function getOneById($id): ?UserInfoModel
    {
        $model = UserInfoModel::findOne($id);
        if (empty($model)) {
            return null;
        }
        if(!empty($model->is_vip)){
            $model->vip_name = UserVipPayModel::findOne(1)['name'];
        }
        return $model;
    }
    public function getOneByOpenId($openId)
    {
        return UserInfoModel::findOne(['open_id'=>$openId]);
    }

    /**
     *  根据小程序openId获取用户信息
     * @param $appOpenId
     * @return array|\yii\db\ActiveRecord|null
     */
    public function getOneByMiniAppOpenId($appOpenId): ?UserInfoModel
    {
        $model = UserInfoModel::find()->where(['mini_app_open_id' => $appOpenId])->one();
        if (empty($model)) {
            return null;
        }
        if(!empty($model->is_vip)){
            $model->vip_name = UserVipPayModel::findOne(1)['name'];
        }
        return $model;
    }
    public function getOneByUnionId($unionId): ?UserInfoModel
    {

        $model = UserInfoModel::find()->where(['union_id' => $unionId])->one();
        if (empty($model)) {
            return null;
        }
        if(!empty($model->is_vip)){
            $model->vip_name = UserVipPayModel::findOne(1)['name'];
        }
        return $model;
    }

    /**
     * 根据Id更新息
     * @param UserInfoModel $model
     * @return int
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function updateUserInfoById(UserInfoModel $model): int
    {
        $model->setOldAttribute('id', $model->id);
        return $model->update();
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
        $array=UserInfoModel::find()->select(['id','head_img','nick_name','identify','telephone','status'])->where(['parent_id'=>$parentId])->all();
        return ArrayHelper::toArray($array);

    }

    /*********************UserScoreDetail模块服务层************************************/
    /**
     * 添加一条用户积分详情
     * @param UserScoreDetailModel $model
     * @return mixed
     * @throws \Throwable
     */
    public function addUserScoreDetail(UserScoreDetailModel $model)
    {
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
        $searchWord = $searchModel->search(array_filter($query->toArray()));
        $result['list'] = ArrayHelper::toArray($searchWord->getModels());
        $result['totalCount'] = $searchWord->pagination->totalCount;
        return $result;
    }

    /**
     * 根据Id获取分详情
     * @param $id
     * @return Object
     */
    public function getScoreDetailById($id)
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
    public function updateUserScoreDetailById(UserScoreDetailModel $model): int
    {
        $model->setOldAttribute('id', $model->id);
        return $model->update();
    }

    /**
     * 用户充值
     * @param $userId
     * @param $amount
     */
    public function chargeUserWallet($userId, $amount)
    {

        UserInfoModel::updateAll(['amount' => new Expression('`amount` + ' . $amount), 'charge_amount' => new Expression('`charge_amount` + ' . $amount)]
            , ['id' => $userId]);
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

    /**
     * 更新最后登陆时间
     * @param $userId
     * @return int
     */
    public function updateLastLoginTime($userId)
    {
        return UserInfoModel::updateAll(['last_log_in_at' => time()], ['id' => $userId]);
    }
}
/**********************End Of UserInfo 服务层************************************/


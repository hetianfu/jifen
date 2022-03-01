<?php

namespace api\modules\mobile\service;

use api\modules\mobile\models\forms\UserScoreDetailModel;
use api\modules\mobile\models\forms\UserScoreModel;
use api\modules\mobile\models\forms\UserScoreQuery;
use fanyou\enums\entity\ScoreConfigEnum;
use fanyou\enums\QueryEnum;
use fanyou\enums\SortEnum;
use fanyou\enums\StatusEnum;
use fanyou\enums\SystemConfigEnum;
use fanyou\models\base\SearchModel;
use fanyou\service\FanYouSystemGroupService;
use fanyou\tools\ArrayHelper;
use fanyou\tools\DaysTimeHelper;
use fanyou\tools\SystemConfigHelper;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-06-01
 */
class UserScoreService
{


    private $userInfoService;
    private $groupService;
    public function __construct()
    {
        $this->userInfoService = new UserInfoService();
        $this->groupService = new SystemGroupService();
    }
    /*********************UserScore模块服务层************************************/

    /**
     * 查询签到的列表
     * @param array $a
     * @param string $name
     * @return mixed
     */
    public function oldgetSignScoreConfig(array $a, $name = ScoreConfigEnum::SIGN_LIST)
    {
        return json_decode($a[$name]);
    }

    public function getSignScoreConfig($configId)
    {
        $groupInfo = $this->groupService->getOneById($configId);
        $gValues = FanYouSystemGroupService::getSystemGroupDate($groupInfo, false);
        return  $gValues['items'];
    }

    /**
     * 添加一条积分配置
     * @param UserScoreModel $model
     * @return mixed
     * @throws \Throwable
     */
    public function addUserScore(UserScoreModel $model)
    {
        $model->insert();
        return $model->getPrimaryKey();
    }

    /**
     * 分页获取列表
     * @param UserScoreQuery $query
     * @return array
     * @throws \yii\web\NotFoundHttpException
     */
    public function getUserScorePage(UserScoreQuery $query): array
    {
        $searchModel = new SearchModel([
            'model' => UserScoreModel::class,
            'scenario' => 'default',
            'partialMatchAttributes' => ['name'], // 模糊查询
            'defaultOrder' => $query->getOrdersArray(),
            'pageSize' => $query->limit,
        ]);
        $searchWord = $searchModel->search($query->toArray([], [QueryEnum::LIMIT]));
        $result['list'] = ArrayHelper::toArray($searchWord->getModels());
        $result['totalCount'] = $searchWord->pagination->totalCount;
        return $result;
    }

    /**
     * 根据Id获取置
     * @param $id
     * @return UserScoreModel|null
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function getOneById($id): ?UserScoreModel
    {
        $info = $this->userInfoService->getOneById($id);
        $model = UserScoreModel::findOne($id);
        if (empty($model)) {

            $model = new UserScoreModel;
//            $model->id=$id;
//            $model->currency_day=1;
//            $model->sign_score=5;
        }
        if (!$this->isContinuity($id)) {
            $model->currency_day = 0;
            $model->setOldAttribute('id', $id);
            $model->update();
        }
        if ($this->isAlreadySign($id)) {
            $model->is_sign = 1;
        }
        $model->total_score = $info->total_score;
//        if($isFirst){
//        $model->insert();
//        }
        return $model;
    }

    /**
     * 根据Id更新置
     * @param UserScoreModel $model
     * @return int
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function updateUserScoreById(UserScoreModel $model): int
    {
        $model->setOldAttribute('id', $model->id);
        return $model->update();
    }

    /**
     * 根据Id删除置
     * @param $id
     * @return int
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function deleteById($id): int
    {
        $model = UserScoreModel::findOne($id);
        return $model->delete();
    }

    /**
     * 获取积分配置
     */
    public function getScoreConfig(): array
    {
        $score = new SystemConfigHelper(SystemConfigEnum::SCORE_CONFIG);
        return $score->getConfigValue();
    }

    /**
     * 用户积分详情首页
     * @param $id
     * @return array
     */
    public function getUserScore($id): array
    {
        $result = [];
        $info = $this->userInfoService->getOneById($id);
        $result['totalScore'] = $info->total_score;
        $totalCost = UserScoreDetailModel::find()
            ->select(['score'=> 'SUM(score)' ])
            ->where(['user_id' => $id,'is_deduct'=>StatusEnum::COME_OUT])
            ->asArray()
            ->one();
        $result['totalCost'] =StatusEnum::COME_OUT* $totalCost['score'];

        $totalGet = UserScoreDetailModel::find()
            ->select(['score'=> 'SUM(score)' ])
            ->where(['user_id' => $id,'is_deduct'=>StatusEnum::COME_IN])
            ->asArray()
            ->one();
        $result['totalGet'] = empty($totalGet['score'] )?0:$totalGet['score'];

        $model = UserScoreDetailModel::find()
            ->where(['user_id' => $id,'is_deduct'=>StatusEnum::COME_IN])
            ->andWhere(['>', 'created_at', DaysTimeHelper::getTodayBeginTime()])
            ->orderBy([SortEnum::CREATED_AT => SORT_DESC])
            ->one();
        $result['todayGet'] = empty($model) ? 0 : $model['score'];

        return $result;
    }


    /**
     * 今日是否已签到
     * @param $userId
     * @return bool
     */
    public function isAlreadySign($userId): bool
    {
        $result = true;
        $model = UserScoreDetailModel::find()
            ->where(['user_id' => $userId,'type' => 'SIGN'])
            ->orderBy([SortEnum::CREATED_AT => SORT_DESC])
            ->one();

        //如果最后一次签到日期==当前日期
        if (!empty($model)) {

            if (!DaysTimeHelper::compareByPoint($model['created_at'])) {
                $result = false;
            }
        } else {
            $result = false;
        }

        return $result;
    }

    /**
     * 是否持续签到
     * @param $userId
     * @return bool
     */
    public function isContinuity($userId): bool
    {
        $result = false;
        $model = UserScoreDetailModel::find()
            ->where(['user_id' => $userId,'type'=>'SIGN'])
            ->orderBy(['created_at' => SORT_DESC])
            ->one();
        //用户最后一次签到时间距离现在大于一天，

        if (!empty($model)) {
            $lastDay = DaysTimeHelper::yesterdayStart(true);
            $model['created_at']>$lastDay  && $result=true;

        }
        return $result;
    }
}
/**********************End Of UserScore 服务层************************************/


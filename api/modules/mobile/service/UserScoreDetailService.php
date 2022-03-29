<?php

namespace api\modules\mobile\service;

use api\modules\mobile\models\forms\UserInfoModel;
use api\modules\mobile\models\forms\UserScoreDetailModel;
use api\modules\mobile\models\forms\UserScoreDetailQuery;
use api\modules\mobile\models\forms\UserScoreModel;
use fanyou\enums\NumberEnum;
use fanyou\enums\SystemArrayEnum;
use fanyou\tools\ArrayHelper;
use fanyou\models\base\SearchModel;
use fanyou\components\SystemConfig;
use fanyou\enums\entity\ScoreConfigEnum;
use fanyou\enums\entity\ScoreTypeEnum;
use fanyou\enums\QueryEnum;
use fanyou\enums\StatusEnum;
use yii\db\Expression;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-04-27
 */
class UserScoreDetailService
{
    private $userInfoService;
    private $scoreService;
    private $scoreConfigService;
    private $systemConfig;
    public function __construct()
    {
        $this->userInfoService = new UserInfoService();
        $this->scoreService = new UserScoreService();
        $this->scoreConfigService = new UserScoreConfigService();
        $this->systemConfig = new SystemConfig();
    }


/*********************UserScoreDetail模块服务层************************************/
	/**
	 * 添加一条用户积分详情
     * 并修改用户总积分--事务
	 * @param UserScoreDetailModel $model
     * @return mixed
     * @throws \Throwable
     */
	public function addUserScoreDetail(UserScoreDetailModel $model)
	{

        if($model->type===ScoreTypeEnum::SIGN){
            $model->is_deduct=StatusEnum::COME_IN;
            $model->score=$this->signUserScore($model->user_id);
        }
        $userInfo=$this->userInfoService->getOneById($model->user_id);
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
			'created_at' => SORT_DESC,
            'id' => SORT_DESC
			],
			'pageSize' => $query->limit,
		]);
		$searchWord = $searchModel->search( $query->toArray([],[QueryEnum::LIMIT]) );
		$result['list'] = ArrayHelper::toArray($searchWord->getModels());
		$result['totalCount'] = $searchWord->pagination->totalCount;
		return $result;
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
     * 获取抵扣积分金额
     * @param $userId
     * @param int $amount
     * @return float
     */
    public function getDeductScoreAmount($userId,$amount=0):float
    {

        // 用户积分抵扣比例  30积分 -->1 元 ，和订单金额抵扣比例不是同一个配置  订单100元可使用 1元
       // SCORE_CONVERT
        $scoreConfig=$this->scoreConfigService->getScoreConfig();
        //需要重新 设计
        $result=0;
        $userInfo=$this->userInfoService->getOneById($userId);
        $userScore=$userInfo->total_score -$userInfo->lock_score;

        if(empty($userScore)){
            return $result;
        }

        $scoreDeduct=$scoreConfig[ScoreConfigEnum::SCORE_DEDUCT];
        if(empty($scoreDeduct) ){
            return $result;
        }

        $scoreConvert=$scoreConfig[ScoreConfigEnum::SCORE_CONVERT];
        if(empty($scoreConvert)){
            return $result;
        }
        //订单总计可以抵扣金额
        $deductAmount=   number_format( $amount*$scoreDeduct ,2) ;
        //用户总积分可以抵扣金额
        $scoreAmount=   number_format( (floatval($userScore)/$scoreConvert),2) ;
        if($deductAmount>$scoreAmount){
            $deductAmount=$scoreAmount;
        }
        return $deductAmount;
    }

    /**
     * 用抵扣金额换取抵扣积分
     * @param int $amount
     * @return int
     */
    public function changeDeductScore($amount=0):int
    {
        $scoreConfig=$this->scoreConfigService->getScoreConfig();
        $scoreConvert=$scoreConfig[ScoreConfigEnum::SCORE_CONVERT];
        if(empty($scoreConvert)){
            return 0;
        }

        return ceil($amount*$scoreConvert);
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

    /**
     * 签到获取积分
     * @param $userId
     * @param int $score
     * @return int
     * @throws \Throwable
     * @throws \yii\web\NotFoundHttpException
     */
    public function  signUserScore(  $userId,$score=0):int{
        $model=$this->scoreService->getOneById($userId);
        //获取配置
        $info= $this->scoreService->getScoreConfig();
        $list=$this->scoreService->getSignScoreConfig($info[ScoreConfigEnum::SIGN_CONFIG]);
        //$list=json_decode($info[ScoreConfigEnum::SIGN_LIST]);
        $type=$info[ScoreConfigEnum::SING_TYPE];
        if(empty($model->id)){
            $score=ArrayHelper::toArray($list[NumberEnum::ZERO])[SystemArrayEnum::SCORE_SING_NUMBER];
            $model=new UserScoreModel();
            $model->id=$userId;
            $model->sign_score=$score;
            $model->total=$model->sign_score;
            $model->currency_day=NumberEnum::ONE;
            $model->sign_days= $model->currency_day;
            $model->insert();
            return $score ;
        }
       //如果不是持续签到，重置
        if(!$this->scoreService->isContinuity($userId)){
            $model->currency_day=NumberEnum::ZERO;
        }

            if ($type==ScoreTypeEnum::REPEAT_SCORE_MODE ){
                if($model->currency_day>=count($list)){
                    $model->currency_day=NumberEnum::ZERO;
                }
                $score=ArrayHelper::toArray($list[$model->currency_day])[SystemArrayEnum::SCORE_SING_NUMBER];
            }  else if($type==ScoreTypeEnum::MAX_SCORE_MODE){
                if($model->currency_day>=count($list)) {
                    $score = ArrayHelper::toArray($list[count($list) - NumberEnum::ONE])[SystemArrayEnum::SCORE_SING_NUMBER];
                }else{
                    $score = ArrayHelper::toArray($list[$model->currency_day])[SystemArrayEnum::SCORE_SING_NUMBER];
                }
            }
        $model->currency_day=$model->currency_day+NumberEnum::ONE;
        UserScoreModel::updateAll(
            [  'currency_day' =>$model->currency_day
                ,'sign_days' => new Expression('`sign_days` + 1')
                ,'sign_score' => new Expression('`sign_score` + '.$score)],
            ['id'=>$model->id]);
        return $score;

    }


}
/**********************End Of UserScoreDetail 服务层************************************/


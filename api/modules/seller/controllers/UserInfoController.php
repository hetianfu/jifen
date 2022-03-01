<?php
namespace api\modules\seller\controllers;

use api\modules\auth\ApiAuth;
use api\modules\seller\models\forms\UserLevelModel;
use api\modules\seller\models\forms\UserLevelQuery;
use api\modules\seller\models\forms\UserScoreDetailModel;
use api\modules\seller\models\forms\UserScoreDetailQuery;
use api\modules\seller\models\forms\UserWalletDetailModel;
use api\modules\seller\service\UserInfoService;
use api\modules\seller\models\forms\UserInfoModel;
use api\modules\seller\models\forms\UserInfoQuery;

use api\modules\seller\service\UserScoreDetailService;
use api\modules\seller\service\UserWalletDetailService;
use fanyou\enums\AppEnum;
use fanyou\enums\entity\ScoreTypeEnum;
use fanyou\enums\HttpErrorEnum;
use fanyou\enums\PayStatusEnum;
use fanyou\enums\StatusEnum;
use fanyou\enums\WalletStatusEnum;
use fanyou\error\FanYouHttpException;
use fanyou\tools\ExcelHelper;
use fanyou\tools\StringHelper;
use Yii;
use yii\web\HttpException;

/**
 * Class UserInfoController
 * @package api\modules\seller\controllers
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-06-09 17:47
 */
class UserInfoController extends BaseController
{

    private $service;
    private $scoreService;
    private $walletService;
    public function init()
    {
        parent::init();
        $this->service = new UserInfoService();
        $this->scoreService = new UserScoreDetailService();
        $this->walletService=new UserWalletDetailService();
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => ApiAuth::class,
           // 'optional'=>['charge-user-wallet','get-user-info-page','get-user-level-list']
        ];
        return $behaviors;
    }
/*********************UserInfo模块控制层************************************/
    /**
     * 将用户拉入黑名单
     * @return mixed
     * @throws FanYouHttpException
     */
    public function actionUpdateBlack(){

        $model = new UserInfoModel(['scenario'=>'update']);
        $model->id=Yii::$app->request->post('id') ;
        $model->status=StatusEnum::DISABLED;
        if ($model->validate()) {
            return $this->service->updateUserInfoById($model);
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($model));
        }
    }

    /**
     * 将用户移出黑名单
     * @return mixed
     * @throws FanYouHttpException
     */
    public function actionUpdateBatchWhite(){

        $model = new UserInfoModel(['scenario'=>'update']);
        $model->id=Yii::$app->request->post('id') ;
        $model->status=StatusEnum::ENABLED;
        if ($model->validate()) {
            return $this->service->updateUserInfoById($model);
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($model));
        }
    }
	/**
	 * 分页获取列表
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionGetUserInfoPage()
	{
		$query = new UserInfoQuery();
		$query->setAttributes($this->getRequestGet());

		if ( $query->validate()) {
			return $this->service->getUserInfoPage( $query);
		} else {
		   throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($query));
		}
	}

    /**
     * 用于搜索用户查找列表
     * @return mixed
     * @throws FanYouHttpException
     */
    public function actionSearchUserInfoPage()
    {
        $query = new UserInfoQuery();
        $query->setAttributes($this->getRequestGet());
        if ( $query->validate()) {
            return $this->service->searchUserInfoPage( $query);
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($query));
        }
    }

	/**
	 * 根据Id获取详情
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionGetUserInfoById(){
		$id = Yii::$app->request->get('id');
		return $this->service->getOneById($id);
	}
	/**
	 * 根据Id更新
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionUpdateUserInfo(){

		$model = new UserInfoModel(['scenario'=>'update']);
		$model->setAttributes($this->getRequestPost(false)) ;
		if ($model->validate()) {
			return $this->service->updateUserInfoById($model);
		} else {
		  throw new FanYouHttpException(HttpErrorEnum::class,parent::getModelError($model));
	   }
	}

	/**
	 * 根据Id删除
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionDelUserInfo(){

		$id = Yii::$app->request->get('id');
		return $this->service->deleteById($id);
		}
/***************************用户等级***********************************/
    /**
     * 添加
     * @return mixed
     * @throws HttpException
     */
    public function actionAddUserLevel()
    {
        $model = new UserLevelModel();
        $model->setAttributes($this->getRequestPost()) ;
        if ($model->validate()) {
            return $this->service->addUserLevel($model);
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Expectation_Failed,parent::getModelError($model));
        }
    }
    /**
     * 分页获取列表
     * @return mixed
     * @throws HttpException
     */
    public function actionGetUserLevelPage()
    {
        $query = new UserLevelQuery();
        $query->setAttributes($this->getRequestGet());
        if ( $query->validate()) {
            return $this->service->getUserLevelPage( $query);
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Expectation_Failed,parent::getModelError($query));
        }
    }
    public function actionGetUserLevelList()
    {
        $query = new UserLevelQuery();
        $query->setAttributes($this->getRequestGet());
        if ( $query->validate()) {
            return $this->service->getUserLevelList( $query);
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Expectation_Failed,parent::getModelError($query));
        }
    }

    /**
     * 根据Id获取详情
     * @return mixed
     * @throws HttpException
     */
    public function actionGetUserLevelById(){
        $id = Yii::$app->request->get('id');
        return $this->service->getUserLevelById($id);
    }
    /**
     * 根据Id更新
     * @return mixed
     * @throws HttpException
     */
    public function actionUpdateUserLevelById(){

        $model = new UserLevelModel(['scenario'=>'update']);
        $model->id=parent::postRequestId();
        $model->level_name=parent::postRequestId('levelName');
        $model->condition_disciple=parent::postRequestId('conditionDisciple');
        $model->condition_amount=parent::postRequestId('conditionAmount');

        if ($model->validate()) {
            return $this->service->updateUserLevelById($model);
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Expectation_Failed,parent::getModelError($model));
        }
    }

    /**
     * 根据Id删除
     * @return mixed
     */
    public function actionDelUserLevelById(){

        $id = Yii::$app->request->get('id');
        return $this->service->deleteUserLevelById($id);
    }
    /***********************用户积分*******************************/
    /**
     * 统计积分
     * @return mixed
     * @throws HttpException
     */
    public function actionStatisticUserScore()
    {   $result=[];
        $query = new UserScoreDetailQuery();
        $query->setAttributes($this->getRequestGet());
        if ( $query->validate()) {
            $query->type=ScoreTypeEnum::SIGN;

//            $userQuery = new UserInfoQuery();
//            $userQuery->setAttributes($this->getRequestGet());
//            $result['totalScore']=$this->service->sumScore($userQuery);

            $result['singNumber']=$this->scoreService->count($query);
            $result['singScore']=$this->scoreService->sum($query);
            $query->type=ScoreTypeEnum::ORDER;

            $result['orderNumber']=$this->scoreService->count($query);
            $result['orderScore']=$this->scoreService->sum($query);
            $query->type=ScoreTypeEnum::ORDER;
            return $result;
            } else {
                throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($query));
            }

    }
     /**
     * 用户增加积分
     * @return mixed
     * @throws HttpException
     */
    public function actionChargeUserScore()
    {
        $model = new UserScoreDetailModel();
        $model->setAttributes($this->getRequestPost()) ;
        $model->is_deduct=StatusEnum::COME_IN;
        $model->type=WalletStatusEnum::CHARGE;
        if ($model->validate()) {
            return  $this->scoreService->addUserScoreDetail($model);
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($model));
        }
    }
    /**
     * 用户扣除积分
     * @return mixed
     * @throws HttpException
     */
    public function actionDeductUserScore()
    {
        $model = new UserScoreDetailModel();
        $model->setAttributes($this->getRequestPost()) ;
        $model->is_deduct=StatusEnum::COME_OUT;
        $model->type=WalletStatusEnum::CHARGE;
        if ($model->validate()) {
            return  $this->scoreService->addUserScoreDetail($model);
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($model));
        }
    }

    /**
     * 分页获取列表
     * @return mixed
     * @throws HttpException
     */
    public function actionGetScoreDetailPage()
    {
        $query = new UserScoreDetailQuery();
        $query->setAttributes($this->getRequestGet());
        if ( $query->validate()) {
            return $this->scoreService->getUserScoreDetailPage( $query);
        } else {
            throw new  FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($query));
        }
    }


    /**
     * 根据Id获取详情
     * @return mixed
     */
    public function actionGetScoreDetailById(){
        return $this->scoreService->getOneById(parent::getRequestId());
    }


    /**
     * 根据Id删除
     * @return mixed
     * @throws HttpException
     */
    public function actionDelUserScoreDetail(){

        return $this->service->deleteById(parent::getRequestId());
    }


    /***********************用户钱包*******************************/
    /**
     * 用户增加钱包金额
     * @return mixed
     * @throws HttpException
     */
    public function actionChargeUserWallet()
    {
        $model = new UserWalletDetailModel();
        $model->setAttributes($this->getRequestPost()) ;
        $model->id=StringHelper::uuid();
        $model->pay_type=PayStatusEnum::WALLET;
        $model->is_deduct=StatusEnum::COME_IN;
        $model->type=WalletStatusEnum::CHARGE;
        $model->operator=parent::getUserCache()['account'];
        if ($model->validate()) {
            return $this->walletService->addUserWalletDetail($model);
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($model));
        }
    }
    /**
     * 用户扣除钱包金额
     * @return mixed
     * @throws HttpException
     */
    public function actionDeductUserWallet()
    {
        $model = new UserWalletDetailModel();
        $model->setAttributes($this->getRequestPost()) ;
        $model->id=StringHelper::uuid();

        $model->pay_type=PayStatusEnum::WALLET;
        $model->is_deduct=StatusEnum::COME_OUT;
        $model->type=WalletStatusEnum::CHARGE;
        $model->operator=parent::getUserCache()['account'];
        if ($model->validate()) {
            return $this->walletService->addUserWalletDetail($model);
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($model));
        }
    }

    /**
     * 导出
     *
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function actionExport()
    {
        $query = new UserInfoQuery();
        $query->setAttributes($this->getRequestGet());

//        $from_date = $request->get('from_date');
//        $to_date = $request->get('to_date');

        $dataList = $this->service->getUserInfoPage( $query);
        $header = [
            ['ID', 'id'],
            ['总积分', 'total_score'],
            ['openid', 'telephone'],
            ['昵称', 'nick_name'],
            ['总积分', 'amount'],
            ['状态', 'status', 'selectd', [  0 => '禁用', 1 => '有效']],
            ['姓别', 'sex', 'selectd', [  '0' => '女', 1 => '男']],
            ['创建时间', 'createdAt', 'date', 'Y-m-d H:i:s'],
            ['时间', 'updatedAt', 'date', 'Y-m-d H:i:s' ],
        ];

        // 导出Excel
        return ExcelHelper::exportData($dataList['list'], $header, '扫描统计_' . time());
    }






	}
/**********************End Of UserInfo 控制层************************************/ 



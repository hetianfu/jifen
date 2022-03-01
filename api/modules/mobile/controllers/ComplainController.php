<?php
namespace api\modules\mobile\controllers;

use api\modules\auth\ApiAuth;
use api\modules\seller\service\ComplainService;
use api\modules\seller\models\forms\ComplainModel;
use api\modules\seller\models\forms\ComplainQuery;
use fanyou\error\FanYouHttpException;
use fanyou\enums\HttpErrorEnum;
use fanyou\service\ThirdSmsService;
use Yii;
use yii\web\HttpException;

/**
 * Class ComplainController
 * @package api\modules\seller\controllers
 * @author: HenryJia
 * @E-mail: 316560925@qq.com
 * @date: 2021-07-31
 */
class ComplainController extends BaseController
{

    private $service; 
    public function init()
    {
        parent::init();
        $this->service = new ComplainService();
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => ApiAuth::class,
        ];
        return $behaviors;
    }
/*********************Complain模块控制层************************************/ 
	/**
	 * 添加
     * @return mixed
     * @throws HttpException
     */
	public function actionAdd()
	{
		$model = new ComplainModel();
		$model->setAttributes($this->getRequestPost()) ;
		if ($model->validate()) {
            $result = $this->service->addComplain($model);
            if ($result) {
                ThirdSmsService::complainSms($model->telephone);
            }
            return $result;
		} else {
			throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($model));
		}
	}
	/**
	 * 分页获取列表
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionGetPage()
	{
		$query = new ComplainQuery();
		$query->setAttributes($this->getRequestGet());
		if ( $query->validate()) {
			return $this->service->getComplainPage( $query);
		} else {
			throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($query));
		}
	}


	/**
	 * 根据Id获取详情
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionGetById(){
		return $this->service->getOneById(parent::getRequestId());
	}

	/**
	 * 根据Id删除
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionDelById(){

		return $this->service->deleteById(parent::getRequestId());
		}
	}
/**********************End Of Complain 控制层************************************/ 



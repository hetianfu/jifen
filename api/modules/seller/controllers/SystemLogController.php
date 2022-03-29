<?php
namespace api\modules\seller\controllers;

use api\modules\auth\ApiAuth;
use api\modules\seller\service\SystemLogService;
use api\modules\seller\models\forms\SystemLogModel;
use api\modules\seller\models\forms\SystemLogQuery;
use fanyou\error\FanYouHttpException;
use fanyou\enums\HttpErrorEnum;
use Yii;
use yii\web\HttpException;

/**
 * SystemLog
 * @author E-mail: Administrator@qq.com
 *
 */
class SystemLogController extends BaseController
{

    private $service; 
    public function init()
    {
        parent::init();
        $this->service = new SystemLogService();
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => ApiAuth::class,
        ];
        return $behaviors;
    }
/*********************SystemLog模块控制层************************************/ 
	/**
	 * 添加
     * @return mixed
     * @throws HttpException
     */
	public function actionAddSystemLog()
	{
		$model = new SystemLogModel();
		$model->setAttributes($this->getRequestPost()) ;
		if ($model->validate()) {
			return $this->service->addSystemLog($model);
		} else {
			throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($query));
		}
	}
	/**
	 * 分页获取列表
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionGetPage()
	{
		$query = new SystemLogQuery();
		$query->setAttributes($this->getRequestGet());
		if ( $query->validate()) {
			return $this->service->getSystemLogPage( $query);
		} else {
			throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($query));
		}
	}


	/**
	 * 根据Id获取详情
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionGetSystemLogById(){
		$id = Yii::$app->request->get('id');
		return $this->service->getOneById($id);
	}
	/**
	 * 根据Id更新
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionUpdateSystemLog(){

		$model = new SystemLogModel(['scenario'=>'update']);
		$model->setAttributes($this->getRequestPost(false)) ;
		if ($model->validate()) {
			return $this->service->updateSystemLogById($model);
		} else {
			throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($model));
	   }
	}

	/**
	 * 根据Id删除
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionDelSystemLog(){

		$id = Yii::$app->request->get('id');
		return $this->service->deleteById($id);
		}
	}
/**********************End Of SystemLog 控制层************************************/ 



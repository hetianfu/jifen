<?php
namespace api\modules\seller\controllers;

use api\modules\auth\ApiAuth;
use api\modules\seller\service\UnitService;
use api\modules\seller\models\forms\UnitModel;
use api\modules\seller\models\forms\UnitQuery;

use fanyou\enums\HttpErrorEnum;
use fanyou\error\FanYouHttpException;
use Yii;
use yii\web\HttpException;

/**
 * Unit
 * @author E-mail: Administrator@qq.com
 *
 */
class UnitController extends BaseController
{

    private $service; 
    public function init()
    {
        parent::init();
        $this->service = new UnitService();
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => ApiAuth::class,
        ];
        return $behaviors;
    }
/*********************Unit模块控制层************************************/ 
	/**
	 * 添加
     * @return mixed
     * @throws HttpException
     */
	public function actionAddUnit()
	{
		$model = new UnitModel();
		$model->setAttributes($this->getRequestPost()) ;
		if ($model->validate()) {
			return $this->service->addUnit($model);
		} else {
		   throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($model));
		}
	}
	/**
	 * 分页获取列表
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionGetUnitPage()
	{
		$query = new UnitQuery();
		$query->setAttributes($this->getRequestGet());
		if ( $query->validate()) {
			return $this->service->getUnitPage( $query);
		} else {
		   throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($query));
		}
	}
    public function actionGetUnitList()
    {
        $query = new UnitQuery();
        $query->setAttributes($this->getRequestGet());
        if ( $query->validate()) {
            return $this->service->getUnitList( $query);
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($query));
        }
    }


	/**
	 * 根据Id获取详情
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionGetUnitById(){
		$id = Yii::$app->request->get('id');
		return $this->service->getUnitById($id);
	}
	/**
	 * 根据Id更新
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionUpdateUnit(){

		$model = new UnitModel(['scenario'=>'update']);
		$model->setAttributes($this->getRequestPost(false)) ;

		if ($model->validate()) {
			return $this->service->updateUnitById($model);
		} else {
		  throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($model));
	   }
	}

	/**
	 * 根据Id删除
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionDelUnit(){

		$id = Yii::$app->request->get('id');
		return $this->service->deleteById($id);
		}
	}
/**********************End Of Unit 控制层************************************/ 



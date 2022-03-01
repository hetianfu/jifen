<?php
namespace api\modules\seller\controllers;

use api\modules\auth\ApiAuth;
use api\modules\seller\service\UserInfoGroupService;
use api\modules\seller\models\forms\UserInfoGroupModel;
use api\modules\seller\models\forms\UserInfoGroupQuery;

use Yii;
use yii\web\HttpException;

/**
 * UserInfoGroup
 * @author E-mail: Administrator@qq.com
 *
 */
class UserInfoGroupController extends BaseController
{

    private $service; 
    public function init()
    {
        parent::init();
        $this->service = new UserInfoGroupService();
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => ApiAuth::class,
        ];
        return $behaviors;
    }
/*********************UserInfoGroup模块控制层************************************/ 
	/**
	 * 添加
     * @return mixed
     * @throws HttpException
     */
	public function actionAddUserInfoGroup()
	{
		$model = new UserInfoGroupModel();
		$model->setAttributes($this->getRequestPost()) ;
		if ($model->validate()) {
			return $this->service->addUserInfoGroup($model);
		} else {
		   throw new \yii\web\UnprocessableEntityHttpException(parent::getModelError($model));
		}
	}
	/**
	 * 分页获取列表
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionGetUserInfoGroupPage()
	{
		$query = new UserInfoGroupQuery();
		$query->setAttributes($this->getRequestGet());
		if ( $query->validate()) {
			return $this->service->getUserInfoGroupPage( $query);
		} else {
		   throw new \yii\web\UnprocessableEntityHttpException(parent::getModelError($query));
		}
	}


	/**
	 * 根据Id获取详情
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionGetUserInfoGroupById(){
		$id = Yii::$app->request->get('id');
		return $this->service->getUserInfoGroupById($id);
	}
	/**
	 * 根据Id更新
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionUpdateUserInfoGroup(){

		$model = new UserInfoGroupModel(['scenario'=>'update']);
		$model->setAttributes($this->getRequestPost(false)) ;
		if ($model->validate()) {
			return $this->service->updateUserInfoGroupById($model);
		} else {
		  throw new \yii\web\UnprocessableEntityHttpException(parent::getModelError($model));
	   }
	}

	/**
	 * 根据Id删除
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionDelUserInfoGroup(){

		$id = Yii::$app->request->get('id');
		return $this->service->deleteById($id);
		}
	}
/**********************End Of UserInfoGroup 控制层************************************/ 



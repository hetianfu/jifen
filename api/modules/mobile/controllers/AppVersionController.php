<?php
namespace api\modules\mobile\controllers;

use api\modules\auth\ApiAuth;
use api\modules\mobile\models\forms\AppVersionQuery;
use api\modules\mobile\service\AppVersionService;
use Yii;

/**
 * Class AppVersionController
 * @package api\modules\seller\controllers
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-07-13
 */
class AppVersionController extends BaseController
{

    private $service; 
    public function init()
    {
        parent::init();
        $this->service = new AppVersionService();
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => ApiAuth::class,
            'optional'=>['get-last-version']
        ];
        return $behaviors;
    }
/*********************AppVersion模块控制层************************************/
	/**
	 * 根据Id获取详情
	 * @return mixed
	 */
	public function actionGetLastVersion(){

	    $query=new AppVersionQuery();
	    $query->appid=Yii::$app->wechat->miniProgram->getConfig()['app_id'];
	    $list=$this->service->getLastVersion($query);
		return empty($list)?0:$list[0];
	}





	}
/**********************End Of AppVersion 控制层************************************/ 



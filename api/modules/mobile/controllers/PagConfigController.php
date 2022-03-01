<?php
namespace api\modules\mobile\controllers;

use api\modules\auth\ApiAuth;
use api\modules\mobile\service\PagConfigService;
use api\modules\mobile\models\forms\PagConfigQuery;
use fanyou\enums\SystemConfigEnum;
use fanyou\error\FanYouHttpException;
use fanyou\enums\HttpErrorEnum;
use fanyou\tools\ArrayHelper;
use fanyou\tools\StringHelper;
use Yii;
use yii\web\HttpException;

/**
 * Class PagConfigController
 * @package api\modules\seller\controllers
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-08-18
 */
class PagConfigController extends BaseController
{

    private $service; 
    public function init()
    {
        parent::init();
        $this->service = new PagConfigService();
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => ApiAuth::class,
            'optional'=>['get-by-title']
        ];
        return $behaviors;
    }
/*********************PagConfig模块控制层************************************/ 

	/**
	 * 分页获取列表
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionGetPage()
	{
		$query = new PagConfigQuery();
		$query->setAttributes($this->getRequestGet());

		if ( $query->validate()) {
			return $this->service->getPagConfigPage( $query);
		} else {
			throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($query));
		}
	}
	/**
	 * 根据title获取详情
	 * @return mixed
	 */
	public function actionGetByTitle(){

	    $title=parent::getRequestId();
        //加入缓存
        $tokenId =SystemConfigEnum::REDIS_PAGE_TITLE.$title;
        $tokenContent=Yii::$app->cache->get($tokenId);
        if(!empty($tokenContent)){
            return   json_decode($tokenContent );
        }
        $result=$this->service->getOneByTitle($title);
        $array=StringHelper::toCamelize(ArrayHelper::toArray($result));
        Yii::$app->cache->set($tokenId, json_encode($array), 600);
		return $array;
	}

	}
/**********************End Of PagConfig 控制层************************************/ 



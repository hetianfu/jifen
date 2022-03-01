<?php
namespace api\modules\mobile\controllers;

use api\modules\auth\ApiAuth;
use api\modules\mobile\models\forms\ShopInfoQuery;
use api\modules\mobile\service\ShopService;
use api\modules\mobile\service\UserScoreConfigService;
use yii\web\HttpException;

/**
 * UserScoreConfig
 * @author E-mail: Administrator@qq.com
 *
 */
class MerchantController extends BaseController
{

    private $service;
    private $shopService;
    public function init()
    {
        parent::init();
        $this->service = new UserScoreConfigService();
        $this->shopService = new ShopService();
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => ApiAuth::class,
            'optional'=>['get-by-id','get-shop-page']
        ];
        return $behaviors;
    }
/*********************UserScoreConfig模块控制层************************************/


	/**
	 * 根据Id获取详情
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionGetScoreConfig(){
		return $this->service->getOne();
	}

    /**
     * 获取门店列表
     * @return mixed
     */
    public function actionGetShopList(){
        $query=new ShopInfoQuery();
        $query->setAttributes(parent::getRequestGet(false));
        return $this->shopService->getList($query);
    }
    /**
     * 获取门店列表
     * @return mixed
     */
    public function actionGetShopPage(){
        $query=new ShopInfoQuery();
        $query->setAttributes(parent::getRequestGet(false));
        return $this->shopService->getPage($query);
    }
    public function actionGetById(){
        return $this->shopService->getById(parent::getRequestId());
    }


}
/**********************End Of UserScoreConfig 控制层************************************/



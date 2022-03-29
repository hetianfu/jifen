<?php
namespace api\modules\mobile\controllers;

use api\modules\auth\ApiAuth;

use api\modules\mobile\models\forms\CategoryQuery;
use api\modules\mobile\service\CategoryService;
use fanyou\enums\HttpErrorEnum;
use fanyou\enums\StatusEnum;
use fanyou\error\FanYouHttpException;
use yii\web\HttpException;

/**
 * zl_category
 * @author E-mail: Administrator@qq.com
 * Create Time: 2019/03/29
 */
class CategoryController extends BaseController
{

    private $service;
    public function init()
    {
        parent::init();
        $this->service = new CategoryService();
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => ApiAuth::class,
            'optional' => ['get-list']
        ];
        return $behaviors;
    } 
/*********************Category模块控制层************************************/

	/**
	 * 获取全部对象列表
	 * @return mixed
	 * @throws HttpException
	 */
public function actionGetList()
	{
		$queryCategory = new CategoryQuery();
        $queryCategory->status= StatusEnum::ENABLED;
         $queryCategory->parentId= StatusEnum::DISABLED;
		if ( $queryCategory->validate()) {
		    return $this->service->getCategoryListWithChild( $queryCategory);
		} else {
		    throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,   parent::getModelError( $queryCategory));
		}
	}


}	
/**********************End Of Category 控制层************************************/ 
 


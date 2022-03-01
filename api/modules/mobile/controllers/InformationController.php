<?php

namespace api\modules\mobile\controllers;

use api\modules\auth\ApiAuth;
use api\modules\seller\models\forms\InformationCategoryQuery;
use api\modules\seller\models\forms\InformationQuery;
use api\modules\seller\service\InformationService;
use fanyou\enums\HttpErrorEnum;
use fanyou\enums\QueryEnum;
use fanyou\enums\StatusEnum;
use fanyou\error\FanYouHttpException;
use fanyou\tools\ArrayHelper;
use Yii;
use yii\web\HttpException;

/**
 * Information
 * @author E-mail: Administrator@qq.com
 *
 */
class InformationController extends BaseController
{

    private $service;

    public function init()
    {
        parent::init();
        $this->service = new InformationService();
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => ApiAuth::class,
            'optional' => ['get-information-page','get-information-by-id', 'get-category-list']
        ];
        return $behaviors;
    }
    /*********************Information模块控制层************************************/


    /**
     * 获取分类列表
     * @return mixed
     * @throws HttpException
     */
    public function actionGetCategoryList()
    {
        $query = new InformationCategoryQuery();
        $query->setAttributes($this->getRequestGet());
        if ($query->validate()) {
            $query->status = 1;
            return $this->service->getInformationCategoryList($query);
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, parent::getModelError($query));
        }
    }

    /**
     * 根据Id获取详情
     * @return mixed
     */
    public function actionGetCategoryById()
    {

        return ArrayHelper::toArray($this->service->getOneCategoryById(parent::getRequestId()));
    }


    /**
     * 分页获取列表
     * @return mixed
     * @throws HttpException
     */
    public function actionGetInformationPage()
    {
        $query = new InformationCategoryQuery();
        $query->status = StatusEnum::ENABLED;
        $query->is_system = StatusEnum::UN_USED;
        $categoryIds = array_column($this->service->getInformationCategoryList($query), "id");

        $query = new InformationQuery();
        $query->setAttributes($this->getRequestGet(false));
        if ($query->validate()) {
            if (empty($query->cid)) {
                if (!empty($categoryIds)){
                    $query->cid = QueryEnum::IN . implode($categoryIds, ',');
                }else{
                    return parent::getEmptyPage();
                }
            }
            $query->is_show = StatusEnum::ENABLED;
            return $this->service->getInformationPage($query);
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, parent::getModelError($query));
        }
    }


    /**
     * 根据Id获取详情
     * @return mixed
     */
    public function actionGetInformationById()
    {
        $id = Yii::$app->request->get('id');
        return ArrayHelper::toArray($this->service->getOneById($id));
    }

}
/**********************End Of Information 控制层************************************/ 



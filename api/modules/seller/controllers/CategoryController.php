<?php

namespace api\modules\seller\controllers;

use api\modules\auth\ApiAuth;
use api\modules\seller\models\event\CategoryEvent;
use api\modules\seller\models\forms\CategoryModel;
use api\modules\seller\models\forms\CategoryQuery;
use api\modules\seller\service\CategoryService;
use fanyou\enums\HttpErrorEnum;
use fanyou\error\FanYouHttpException;
use Yii;
use yii\web\HttpException;


/**
 * zl_category
 * @author E-mail: Administrator@qq.com
 * Create Time: 2019/03/29
 */
class CategoryController extends BaseController
{

    private $service;
    const EVENT_SAVE_CATEGORY = 'save-category';
   // const EVENT_DEL_CATEGORY = 'del-category';
    public function init()
    {
        parent::init();
        $this->service = new CategoryService();
        $this->on(self::EVENT_SAVE_CATEGORY,['api\modules\seller\service\event\CategoryEventService','saveCategory']);
       // $this->on(self::EVENT_DEL_CATEGORY,['api\modules\seller\service\event\CategoryEventService','deleteCategory']);
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => ApiAuth::class,
            'optional' => ['get-recursion-category-id-by-id']
        ];
        return $behaviors;
    }
    /*********************Category模块控制层************************************/

    /**
     * 获取分类及下属子分类Id
     * @return mixed
     */
    public function actionGetRecursionCategoryIdById()
    {
        return $this->service->getRecursionCategoryById(parent::getRequestId());
    }

    /**
     * 添加一条分类，同时存储于基础包
     * @return mixed
     * @throws HttpException
     */
    public function actionAddCategory()
    {
        $model = new CategoryModel();
        $model->setAttributes($this->getRequestPost(true, true));

        if ($model->validate()) {
            return $this->service->addCategory($model);
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Expectation_Failed,parent::getModelError($model));
        }

    }

    /**
     * 分页获取对象列表
     * @return mixed
     * @throws HttpException
     */
    public function actionGetCategoryPage()
    {
        $queryCategory = new CategoryQuery();
        $queryCategory->setAttributes($this->getRequestGet());
        if ($queryCategory->validate()) {
            return $this->service->getCategoryPage($queryCategory);
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Expectation_Failed,parent::getModelError($queryCategory));
        }
    }
    /**
     * 获取全部对象列表
     * @return mixed
     * @throws HttpException
     */
    public function actionGetCategoryList()
    {

        $queryCategory = new CategoryQuery();

        $queryCategory->attributes = $this->getRequestGet();
        if ($queryCategory->validate()) {
            return $this->service->getRecursionCategoryList($queryCategory);
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Expectation_Failed,parent::getModelError($queryCategory));
        }
    }

    /**
     * 根据Id获取单条对象
     * @return mixed
     * @throws HttpException
     */
    public function actionGetCategoryById()
    {
        return $this->service->getCategoryById(parent::getRequestId());
    }

    /**
     * 更新分类
     * @return mixed
     * @throws HttpException
     */
    public function actionUpdateCategory()
    {
        $updateModel = new CategoryModel(['scenario' => 'update']);
        $updateModel->setAttributes($this->getRequestPost());
        if ($updateModel->validate()) {
            $result=$this->service->updateCategory($updateModel);
            if ($result && !empty($updateModel->name)) {
                $old = $this->service->getOneById($updateModel->id);
                if (  $updateModel->name !== $old['name']) {
                    //如果修改名称，则查所有商品Id,修改名称
                    $event=new CategoryEvent();
                    $event->categoryId=$updateModel->id;
                    $event->categoryName=$old['name'];
                    $event->updateName=$updateModel->name;
                    $this->trigger(self::EVENT_SAVE_CATEGORY,$event);
                }
            }
            return $result;
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Expectation_Failed,parent::getModelError($updateModel));
        }
    }
    /**
     * 根据Id删除分类
     * @return mixed
     */
    public function actionDelCategory()
    {
        return $this->service->deleteCategory(parent::getRequestId());

    }

}
/**********************End Of Category 控制层************************************/ 
 


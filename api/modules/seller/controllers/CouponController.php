<?php

namespace api\modules\seller\controllers;

use api\modules\auth\ApiAuth;
use api\modules\seller\models\forms\CouponTemplateModel;
use api\modules\seller\models\forms\CouponTemplateQuery;
use api\modules\seller\service\CategoryService;
use api\modules\seller\service\CouponService;
use api\modules\seller\models\forms\CouponModel;
use api\modules\seller\models\forms\CouponQuery;

use fanyou\enums\entity\CouponEnum;
use fanyou\enums\HttpErrorEnum;
use fanyou\error\FanYouHttpException;
use Yii;
use yii\web\HttpException;

/**
 * Coupon
 * @author E-mail: Administrator@qq.com
 *
 */
class CouponController extends BaseController
{

    private $service;
    private $categoryService;

    public function init()
    {
        parent::init();
        $this->service = new CouponService();
        $this->categoryService = new CategoryService();
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => ApiAuth::class,
            'optional' => ['get-coupon-page']
        ];
        return $behaviors;
    }
    /*********************Coupon模块控制层************************************/

    /**
     * 添加
     * @return mixed
     * @throws HttpException
     */
    public function actionAddCouponTemplate()
    {
        $model = new CouponTemplateModel();
        $model->setAttributes($this->getRequestPost());
        if ($model->validate()) {
            switch ($model->type) {
                case CouponEnum::CATEGORY:
                    $array = $this->categoryService->getRecursionCategoryById($model->type_relation_id);
                    $model->type_relation_id = implode(',', $array);
                    break;
            }
            return $this->service->addCouponTemplate($model);
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, parent::getModelError($model));
        }
    }

    /**
     * 分页获取列表
     * @return mixed
     * @throws HttpException
     */
    public function actionGetCouponTemplatePage()
    {
        $query = new CouponTemplateQuery();
        $query->setAttributes($this->getRequestGet());
        if ($query->validate()) {
            return $this->service->getCouponTemplatePage($query);
        } else {
            throw new  FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, parent::getModelError($query));
        }
    }

    public function actionGetCouponTemplateList()
    {
        $query = new CouponTemplateQuery();
        $query->setAttributes($this->getRequestGet());
        if ($query->validate()) {
            return $this->service->getCouponTemplateList($query);
        } else {
            throw new  FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, parent::getModelError($query));
        }
    }

    /**
     * 根据Id获取详情
     * @return mixed
     * @throws HttpException
     */
    public function actionGetCouponTemplateById()
    {
        $id = Yii::$app->request->get('id');
        return $this->service->getCouponTemplateById($id);
    }

    /**
     * 根据Id更新
     * @return mixed
     * @throws HttpException
     */
    public function actionUpdateCouponTemplate()
    {

        $model = new CouponTemplateModel(['scenario' => 'update']);
        $model->setAttributes($this->getRequestPost(false));
        if ($model->validate()) {
            return $this->service->updateCouponTemplateById($model);
        } else {
            throw new  FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, parent::getModelError($model));
        }
    }

    /**
     * 根据Id删除
     * @return mixed
     */
    public function actionDelCouponTemplateById()
    {
        $id = Yii::$app->request->get('id');
        //该模版下是否存在优惠券
        $count = CouponModel::find()->where(['template_id' => $id])->count();
        if ($count) {
            throw new  FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, '模版下存在已发售优惠券！');
        }
        return $this->service->deleteTemplateById($id);
    }

    /*******************************优惠券管理*******************************************/
    /**
     * 生成优惠券
     * @return mixed
     * @throws HttpException
     */
    public function actionPublishCoupon()
    {
        $model = new CouponModel();
        $model->setAttributes($this->getRequestPost());
        if ($model->validate()) {
            $userCache = parent::getUserCache();
            $model->editor = $userCache['account'];
            $model->left_number = $model->limit_number;
            return $this->service->addCoupon($model);
        } else {
            throw new  FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, parent::getModelError($model));
        }
    }

    /**
     * 分页获取列表
     * @return mixed
     * @throws HttpException
     */
    public function actionGetCouponPage()
    {
        $query = new CouponQuery();
        $query->setAttributes($this->getRequestGet());
        if ($query->validate()) {
            //   print_r($query->toArray());exit;
            return $this->service->getCouponPage($query);
        } else {
            throw new  FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, parent::getModelError($query));
        }
    }

    /**
     * 根据Id获取详情
     * @return mixed
     * @throws HttpException
     */
    public function actionGetCouponById()
    {
        return $this->service->getCouponById(parent::getRequestId());
    }

    /**
     * 根据Id更新
     * @return mixed
     * @throws HttpException
     */
    public function actionUpdateCoupon()
    {

        $model = new CouponModel(['scenario' => 'update']);
        $model->setAttributes($this->getRequestPost(false));
        if ($model->validate()) {
            return $this->service->updateCouponById($model);
        } else {
            throw new  FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, parent::getModelError($model));
        }
    }

    /**
     * 根据Id删除
     * @return mixed
     * @throws HttpException
     */
    public function actionDelCouponById()
    {

        return $this->service->deleteById(parent::getRequestId());
    }


}
/**********************End Of Coupon 控制层************************************/ 



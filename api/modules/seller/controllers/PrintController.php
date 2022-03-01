<?php

namespace api\modules\seller\controllers;

use api\modules\auth\ApiAuth;
use api\modules\seller\models\forms\PrintModel;
use api\modules\seller\models\forms\PrintQuery;
use api\modules\seller\service\PrintService;
use fanyou\enums\entity\PrintBrandEnum;
use fanyou\enums\entity\PrintConfigEnum;
use fanyou\enums\HttpErrorEnum;
use fanyou\error\FanYouHttpException;
use fanyou\tools\PrintHelper;
use Yii;
use yii\web\HttpException;

/**
 * Print
 * @author E-mail: Administrator@qq.com
 *
 */
class PrintController extends BaseController
{
    private $service;

    public function init()
    {
        parent::init();
        $this->service = new PrintService();
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => ApiAuth::class,
        ];
        return $behaviors;
    }
    /*********************Print模块控制层************************************/
    /**
     * 添加打印机
     * @return mixed
     * @throws HttpException
     */
    public function actionAddPrint()
    {
        $model = new PrintModel();
        $model->setAttributes($this->getRequestPost());
        if ($model->validate()) {
            if ($model->brand == PrintBrandEnum::Y_L_YUN) {
                $print = new PrintHelper($model, PrintBrandEnum::Y_L_YUN);
            } else {
                $print = new PrintHelper($model);
            }
            if (!empty($print->addPrint())) {
                return $this->service->addPrint($model);
            }
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, parent::getModelError($model));
        }
    }

    /**
     * 分页获取打印机列表
     * @return mixed
     * @throws HttpException
     */
    public function actionGetPrintPage()
    {
        $query = new PrintQuery();
        $query->setAttributes($this->getRequestGet());
        if ($query->validate()) {
            $list = $this->service->getPrintPage($query);
            return $list;
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, parent::getModelError($query));
        }
    }
    /**
     * 根据Id获取打印机详情
     * @return mixed
     */
    public function actionGetPrintById()
    {

        $model = $this->service->getOneById(parent::getRequestId());
        if (!empty($model)) {
            if ($model['brand'] == PrintBrandEnum::Y_L_YUN) {
                $print = new PrintHelper([PrintConfigEnum::PRINT_SN => $model[PrintConfigEnum::PRINT_SN]], PrintBrandEnum::Y_L_YUN);
            } else {
                $print = new PrintHelper([PrintConfigEnum::PRINT_SN => $model[PrintConfigEnum::PRINT_SN]]);
            }

            $model['status'] = $print->checkPrintStatus();
        }
        return $model;
    }

    /**
     * 根据Id更新打印机
     * @return mixed
     * @throws HttpException
     */
    public function actionUpdatePrintById()
    {

        $model = new PrintModel(['scenario' => 'update']);
        $model->setAttributes($this->getRequestPost(false));
        if ($model->validate()) {
            return $this->service->updatePrintById($model);
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, parent::getModelError($model));
        }
    }

    /**
     * 根据Id删除打印机
     * @return mixed
     */
    public function actionDeletePrintById()
    {
        $model = $this->service->getOneById(parent::getRequestId());
        if (!empty($model)) {
            if ($model->brand == PrintBrandEnum::Y_L_YUN) {
                $print = new PrintHelper($model, PrintBrandEnum::Y_L_YUN);
            } else {
                $print = new PrintHelper($model);
            }
            $print->delPrint();
        }
        return $this->service->deleteById(parent::getRequestId());
    }

    /**
     * 测试打印机
     * @return mixed
     */
    public function actionPrintTest()
    {

        $print = new PrintHelper([PrintConfigEnum::PRINT_SN => parent::getRequestId('printSn')]);

        return json_decode($print->printTest());
    }


}
/**********************End Of Print 控制层************************************/ 



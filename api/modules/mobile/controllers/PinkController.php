<?php

namespace api\modules\mobile\controllers;

use api\modules\auth\ApiAuth;
use api\modules\mobile\models\forms\PinkConfigQuery;
use api\modules\mobile\models\forms\PinkPartakeQuery;
use api\modules\mobile\models\forms\PinkQuery;
use api\modules\mobile\models\forms\ProductQuery;
use api\modules\mobile\service\PinkConfigService;
use api\modules\mobile\service\PinkService;
use api\modules\mobile\service\ProductService;
use fanyou\enums\entity\SystemGroupEnum;
use fanyou\enums\HttpErrorEnum;
use fanyou\enums\NumberEnum;
use fanyou\enums\QueryEnum;
use fanyou\enums\StatusEnum;
use fanyou\error\FanYouHttpException;
use yii\web\HttpException;

/**
 * Class PinkController
 * @package api\modules\seller\controllers
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-08-20
 */
class PinkController extends BaseController
{

    private $service;
    private $configService;
    private $productService;

    public function init()
    {
        parent::init();
        $this->service = new PinkService();
        $this->configService = new PinkConfigService();
        $this->productService = new ProductService();
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => ApiAuth::class,
            'optional' => ['get-pink-product-ids', 'get-pink-product-page', 'get-by-product-id', 'get-by-id', 'add', 'get-partake-list']
        ];
        return $behaviors;
    }
    /*********************Pink模块控制层************************************/
    /**
     * 添加
     * @return mixed
     * @throws HttpException
     */
    public function actionAdd()
    {
        return 0;

    }

    /**
     * 根据商品取拼团信息
     * @return mixed
     */
    public function actionGetByProductId()
    {
        $userId = parent::getUserId();

        $pink = $this->service->getByProductId(parent::getRequestId('productId'), $userId);

        return $pink;
    }

    /**
     * 根据订单号取当前用户参与的正在进行的团购详情
     * @return mixed
     */
    public function actionGetByOrderId()
    {
        //取当前生效的所有拼团列表
        //取当前进行中的拼团Id
        $query = new PinkPartakeQuery();
        $query->user_id = parent::getUserId();
        $query->order_id = parent::getRequestId();
        $query->status = NumberEnum::ONE;
        //获取当前用户已经参与的的拼团记录
        $userTakePart = $this->service->getOnePartake($query);
        if (!empty($userTakePart)) {
            $pinkId=$userTakePart['pink_id'];
            //重组拼团内容
            $pink = $this->service->getOneById($pinkId);
            if (!empty($pink)) {
                $queryPart = new PinkPartakeQuery();
                $queryPart->pink_id = $pinkId;
                $queryPart->status = NumberEnum::ONE;
                $pink['partakeList'] = $this->service->getPartakeList($queryPart);
            }
            return $pink;
        }
        return null;
    }


    /**
     * 取当前用户参与的正在进行的团购详情
     * @return mixed
     */
    public function actionGetById()
    {
        $userId = parent::getUserId();
        //取当前生效的所有拼团列表
        $pinkList = $this->service->getPinkListByProductId(parent::getRequestId());
        if (count($pinkList)) {
            //取当前进行中的拼团Id
            $pinkIds = implode(',', array_column($pinkList, 'id'));
            $query = new PinkPartakeQuery();
            $query->user_id = $userId;
            $query->pink_id = QueryEnum::IN . $pinkIds;
            $query->status = NumberEnum::ONE;
            //获取当前用户已经参与的的拼团记录
            $userTakePart = $this->service->getOnePartake($query);
            if (!empty($userTakePart)) {
                //重组拼团内容
                $pink = $this->service->getOneById($userTakePart['pinkId']);
                if (!empty($pink)) {
                    $queryPart = new PinkPartakeQuery();
                    $queryPart->pink_id = $userTakePart['pinkId'];
                    $queryPart->status = NumberEnum::ONE;
                    $pink['partakeList'] = $this->service->getPartakeList($queryPart);
                }
                return $pink;
            }
        }
        return null;
    }

    /**
     * 获取拼团商品Id
     * @return mixed
     * @throws HttpException
     */
    public function actionGetPinkProductIds()
    {
        $query = new PinkQuery();
        $query->setAttributes($this->getRequestGet());
        if ($query->validate()) {
            return $this->configService->getPinkProductIds($query);
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, parent::getModelError($query));
        }
    }

    /**
     * 获取拼团商品列表
     * @return mixed
     * @throws FanYouHttpException
     */
    /**
     * 获取拼团商品列表
     * @return mixed
     * @throws FanYouHttpException
     */
    public function actionGetPinkProductPage()
    {
        $pinkQuery = new PinkConfigQuery();
        $pinkQuery->setAttributes($this->getRequestGet());
        if ($pinkQuery->validate()) {
            $query = new ProductQuery();
            $query->id = QueryEnum::IN . $this->configService->getPinkProductIds($pinkQuery);
            $query->is_on_sale = StatusEnum::ENABLED;
            $query->sale_strategy = SystemGroupEnum::PINK_TYPE;
            return $this->productService->getProductPage($query);
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, parent::getModelError($pinkQuery));
        }
    }

    /**
     * 分页获取列表
     * @return mixed
     * @throws HttpException
     */
    public
    function actionGetPinkPage()
    {
        $query = new PinkQuery();
        $query->setAttributes($this->getRequestGet());
        if ($query->validate()) {
            return $this->service->getPartakeList($query);
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, parent::getModelError($query));
        }
    }

    /**
     * 取参与人列表
     * @return mixed
     * @throws FanYouHttpException
     */
    public function actionGetPartakeList()
    {
        $query = new PinkPartakeQuery();
        $query->setAttributes($this->getRequestGet());
        if ($query->validate()) {

            return $this->service->getPartakeList($query);
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, parent::getModelError($query));
        }

    }
}
/**********************End Of Pink 控制层************************************/ 



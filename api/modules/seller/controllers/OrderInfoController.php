<?php

namespace api\modules\seller\controllers;

use api\modules\auth\ApiAuth;
use api\modules\mobile\models\forms\UserInfoModel;
use api\modules\seller\models\event\OrderEvent;
use api\modules\seller\models\forms\BasicOrderInfoModel;
use api\modules\seller\models\forms\BasicOrderInfoQuery;
use api\modules\seller\models\forms\ProductStockDetailQuery;
use api\modules\seller\service\BasicOrderInfoService;
use api\modules\seller\service\ProductStockService;
use EasyWeChat\Kernel\Support\Arr;
use fanyou\enums\DateQueryEnum;
use fanyou\enums\entity\OrderStatusEnum;
use fanyou\enums\HttpErrorEnum;
use fanyou\enums\PayStatusEnum;
use fanyou\enums\QueryEnum;
use fanyou\error\FanYouHttpException;
use fanyou\service\PrintService;
use fanyou\service\ThirdSmsService;
use fanyou\service\WuLiuService;
use fanyou\tools\ArrayHelper;
use fanyou\tools\ExcelHelper;
use fanyou\tools\StringHelper;
use yii\web\HttpException;
use yii\web\UnprocessableEntityHttpException;
use yii\web\UploadedFile;

/**
 * Class OrderInfoController
 * @package api\modules\seller\controllers
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-06-13 9:11
 */
class OrderInfoController extends BaseController
{

    private $service;
    private $pStockService;
    const EVENT_CHECK_ORDER = 'check_order';

    const EVENT_ORDER_SEND = 'send_order';

    public function init()
    {
        parent::init();
        $this->service = new BasicOrderInfoService();
        $this->pStockService = new ProductStockService();
        //定义订阅事件-核销订单
        $this->on(self::EVENT_CHECK_ORDER, ['api\modules\seller\service\event\OrderEventService', 'checkOrder']);

        $this->on(self::EVENT_ORDER_SEND, ['api\modules\seller\service\event\MessageEventService', 'orderSendMessage']);
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => ApiAuth::class,
            'optional' => ['export', 'import-order-express-excel'],
        ];
        return $behaviors;
    }
    /*********************BasicOrderInfo模块控制层************************************/
    /**
     * 查询快递
     * @return array|mixed|object|string
     * @throws FanYouHttpException
     */
    public function actionQueryKdi()
    {
        return WuLiuService::queryKdi(parent::getRequestId("expressNo"));
    }


    /**
     * 添加
     * @return mixed
     * @throws HttpException
     */
    public function actionAddBasicOrderInfo()
    {
        $model = new BasicOrderInfoModel();
        $model->setAttributes($this->getRequestPost());
        if ($model->validate()) {
            return $this->service->addBasicOrderInfo($model);
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, parent::getModelError($model));
        }
    }

    /**
     * 获取订单列表头部统计数据
     * @return mixed
     */
    public function actionCountOrderInfoTitle()
    {
        $array['status'] = array_merge(OrderStatusEnum::countStatusInit(), $this->service->groupOrderInfo('status'));
        $array['pay_type'] = array_merge(PayStatusEnum::countPayStatusInit(), $this->service->groupOrderInfo('pay_type'));
        $array['distribute'] = $this->service->groupOrderInfo('distribute');
        $array['queryDay'] = DateQueryEnum::queryDayInit();
        $array['type'] = $this->service->groupOrderInfo('type');
        return $array;
    }

    /**
     * 获取订单列表头部统计数据
     * @return mixed
     * @throws HttpException
     */
    public function actionSumOrderInfoTitle()
    {

        //查询，已支付 ，  订单数量，订单金额， 退款金额，运费金额，分佣金额

        //再查询 订单类型  不用查询

        //再查询微信，，余额
        // 再查询分佣，
        $result = [];
        $query = new BasicOrderInfoQuery();
        $query->setAttributes($this->getRequestGet());

        if ($query->validate()) {
            $result['countStatus'] = $this->service->countBasicOrderInfo($query);

            $result['sumStatus'] = $this->service->sumEffectOrderInfo($query);
            $result['sumPayType'] = $this->service->sumGroupOrderInfo($query, 'pay_type');
//        if( empty($query->pay_type)){
//            //按支付方式分组,哪个参数没传，就按哪个group
//            $this->service->groupOrderInfo('status');
//        }
            //        print_r($result);exit;
            return $result;
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, parent::getModelError($query));
        }
    }

    /**
     * 分页获取列表
     * @return mixed
     * @throws HttpException
     */
    public function actionGetOrderInfoPage()
    {
        $query = new BasicOrderInfoQuery();
        $query->setAttributes($this->getRequestGet());
        if ($query->validate()) {
            //$query->product_id=3;
            if (!empty($query->product_id)) {
                $stockQuery = new ProductStockDetailQuery();
                $stockQuery->product_id = $query->product_id;
                $stockQuery->page = $query->page;
                $stockQuery->limit = $query->limit;
                $stockQuery->limit = $query->limit;
                $stockQuery->ge_created_at = $query->ge_created_at;
                $stockQuery->gt_created_at = $query->gt_created_at;
                $stockQuery->le_created_at = $query->le_created_at;
                $orderIds = $this->pStockService->getDistinctOrderByProductId($stockQuery);
                $queryId = QueryEnum::IN . implode(',', array_column($orderIds['list'], 'order_id'));
                $query->id = $queryId;
                $totalCount = $orderIds['totalCount'];
                //如果查商品，则使用In查询，条数由商品 管控
                $result = $this->service->getBasicOrderInfoPage($query, false);
                $result['totalCount'] = $totalCount;
            } else {
                $result = $this->service->getBasicOrderInfoPage($query);
            }
            return $result;
        } else {
            throw new  FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, parent::getModelError($query));
        }
    }


    /**
     * 根据Id获取详情
     * @return mixed
     */
    public function actionGetOrderInfoById()
    {

        return $this->service->getOneById(parent::getRequestId());
    }

    /**
     * 根据Id更新
     * @return mixed
     * @throws HttpException
     */
    public function actionUpdateById()
    {

        $model = new BasicOrderInfoModel(['scenario' => 'update']);
        $model->setAttributes($this->getRequestPost(false));
        if ($model->validate()) {
            return $this->service->updateBasicOrderInfoById($model);
        } else {
            throw new  FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, parent::getModelError($model));
        }
    }

    /**
     * 根据Id删除
     * @return mixed
     */
    public function actionDelOrderInfo()
    {
        $id = parent::getRequestId();
        $arr = explode(",", $id);
        if (count($arr)) {
            foreach ($arr as $i) {
                $this->service->deleteById($i);
            }
        }
        return count($arr);

    }

    /**
     * 打印订单
     */
    public function actionPrintOrder()
    {
        return json_decode(PrintService::printOrder($this->service->getOneById(parent::postRequestId())));
    }

    /**
     * 提醒用户支付
     * @return mixed
     * @throws UnprocessableEntityHttpException
     */
    public function actionDoReminder()
    {
        $id = parent::postRequestId();
        $ids = explode(',', $id);
        if (count($ids) && !empty($_ENV['THIRD_SMS_STATUS'])) {
            foreach ($ids as $id) {
                $orderInfo = BasicOrderInfoModel::find()->select(['connect_snap'])->where(['id' => $id])->one();
                $array = ArrayHelper::toArray(json_decode($orderInfo['connect_snap']));
                ThirdSmsService::cuiSms($array['name'], $array['telephone']);
            }
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, '此功能暂未开放');
        }
        return count($ids);
    }

    /**
     * 提醒用户支付
     * @return mixed
     * @throws UnprocessableEntityHttpException
     */
    public function actionSendSmsMsg()
    {
        $id = parent::postRequestId();
        $ids = explode(',', $id);
        if (count($ids) && !empty($_ENV['THIRD_SMS_STATUS'])) {
            foreach ($ids as $id) {
                $orderInfo = BasicOrderInfoModel::find()->select(['connect_snap', 'express_no'])->where(['id' => $id])->one();
                $array = ArrayHelper::toArray(json_decode($orderInfo['connect_snap']));
                ThirdSmsService::sendSms($array['name'], $array['telephone'], $orderInfo['express_no']);
            }
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, '此功能暂未开放');
        }
        return count($ids);
    }

    /**
     * 核销订单卡券
     * @return mixed
     */
    public function actionCheckOrder()
    {
        //改变订单状态
        $model = new BasicOrderInfoModel(['scenario' => 'update']);
        $model->setAttributes($this->getRequestPost(false));
        if ($model->validate()) {
            $model->status = OrderStatusEnum::CLOSED;
            $result = $this->service->updateBasicOrderInfoById($model);
            if ($result) {
                $event = new OrderEvent();
                $event->id = $model->id;
                $this->trigger(self::EVENT_CHECK_ORDER, $event);
            }
            return $result;
        }
    }

    /**
     * 发货
     * @return mixed
     * @throws HttpException
     */
    public function actionSendOrderInfo()
    {
        $model = new BasicOrderInfoModel(['scenario' => 'update']);
        $model->setAttributes($this->getRequestPost(false));
        if ($model->validate()) {
            //已发货
            $model->status = OrderStatusEnum::SENDING;
            $result = $this->service->updateBasicOrderInfoById($model);

            if ($result) {
                $event = new OrderEvent();
                $event->id = $model->id;
                $this->trigger(self::EVENT_ORDER_SEND, $event);
            }
            return $result;
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, parent::getModelError($model));
        }
    }

    public function actionExport()
    {
        $query = new BasicOrderInfoQuery();
        $query->setAttributes($this->getRequestGet());
        $dataList = $this->service->getOrderList($query);
        $list = [];
        foreach ($dataList as $k => $data) {
            $list[$k]['id'] = $data['id'];
            $list[$k]['pay_amount'] = $data['pay_amount'];
            $list[$k]['supply_name'] = $data['supply_name'];
            $productSnap = json_decode($data['cart_snap']);
            $productList = ArrayHelper::toArray($productSnap);
            $list[$k]['product_id'] = $productList[0]['id'];
            $list[$k]['product_name'] = $productList[0]['name'];
            $list[$k]['product_number'] = $productList[0]['number'];
            $list[$k]['product_cost'] = $productList[0]['costAmount'];

            $list[$k]['spec_snap'] = empty($productList[0]['specSnap']) ? '' : $productList[0]['specSnap'];

            if (!empty($data['address_snap'])) {
                $address = ArrayHelper::toArray(json_decode($data['address_snap']));
                $list[$k]['telephone'] = $address['telephone'];
                $list[$k]['name'] = $address['name'];
                $list[$k]['address'] = $address['city'] . $address['detail'];
            } else if (!empty($data['connect_snap'])) {
                $connect = ArrayHelper::toArray(($data['connect_snap']));
                $list[$k]['telephone'] = isset($connect['telephone']) ? $connect['telephone'] : '';
                $list[$k]['name'] = isset($connect['name']) ? $connect['name'] : '';
                if (isset($connect['city']) && isset($connect['detail'])) {
                    $list[$k]['address'] = $connect['city'] . $connect['detail'];
                } else {
                    $list[$k]['address'] = '';
                }
            }

            $list[$k]['pay_type'] = $data['pay_type'];
            $list[$k]['status'] = $data['status'];
            $list[$k]['paid_time'] = $data['paid_time'];
            $list[$k]['expressName'] = $data['express_name'];
            $list[$k]['expressNo'] = $data['express_no'];
            $list[$k]['remark'] = $data['remark'];
        }

        $header = [
            ['订单编号', 'id'],
            ['总金额', 'pay_amount'],
            ['供应商', 'supply_name'],
            ['商品Id', 'product_id'],
            ['商品名称', 'product_name'],
            ['商品规格', 'spec_snap'],
            ['商品数量', 'product_number'],
            ['商品成本', 'product_cost'],

            ['名字', 'name'],
            ['联系电话', 'telephone'],
            ['地址', 'address'],
            ['支付方式', 'pay_type', 'selectd', PayStatusEnum::getMap()],
            ['状态', 'status', 'selectd', OrderStatusEnum::getMap()],
            ['支付时间', 'paid_time', 'date', 'Y-m-d H:i:s'],
            ['快递公司', 'expressName'],
            ['快递单号', 'expressNo'],
            ['备注', 'remark'],
        ];
        // 导出Excel
        return ExcelHelper::exportData($list, $header, '订单信息_' . time(), '订单导出');
    }

    public function actionImportOrderExpressExcel()
    {
        $file = UploadedFile::getInstanceByName('file');
        $i = 0;
        $dataArray = ExcelHelper::import($file->tempName);
        if (is_array($dataArray)) {
            foreach ($dataArray as $k => $data) {
//                $model = new BasicOrderInfoModel(['scenario' => 'update']);
//                $model->id = $data[0];
//                $model->express_name = $data[1];
//                $model->express_no = $data[2];
//                $model->status = 'sending';
                $reset = BasicOrderInfoModel::updateAll(['express_name' => $data[1], 'express_no' => $data[2], 'status' => 'sending']
                    , ['and', ['id' => $data[0]], ['!=', 'status', 'unpaid']]);

                // $reset = $this->service->updateBasicOrderInfoById($model);
                if ($reset) {
                    if (!empty($_ENV['THIRD_SMS_STATUS'])) {
                        $orderInfo = BasicOrderInfoModel::find()->select(['connect_snap'])->where(['id' => $data[0]])->one();
                        $array = ArrayHelper::toArray(json_decode($orderInfo['connect_snap']));
                        ThirdSmsService::sendSms($array['name'], $array['telephone'], $data[2]);
                    }
                    $i++;
                }
            }
        }
        return $i;
    }


}
/**********************End Of BasicOrderInfo 控制层************************************/ 



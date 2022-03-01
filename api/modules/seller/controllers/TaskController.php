<?php

namespace api\modules\seller\controllers;

use api\modules\auth\ApiAuth;
use api\modules\seller\models\forms\PagConfigModel;
use api\modules\seller\models\forms\PinkModel;
use api\modules\seller\models\forms\PinkPartakeModel;
use api\modules\seller\models\forms\ProductModel;
use api\modules\seller\models\forms\ProductSkuModel;
use api\modules\seller\models\forms\TaskTaskModel;
use api\modules\seller\models\forms\TaskTaskQuery;
use api\modules\seller\service\TaskTaskService;
use fanyou\enums\HttpErrorEnum;
use fanyou\enums\StatusEnum;
use fanyou\error\FanYouHttpException;
use fanyou\queue\RefundOrderJob;
use fanyou\service\SendWxMsgService;
use fanyou\tools\ArrayHelper;
use Yii;
use yii\db\Expression;

/**
 * zl_category
 * @author E-mail: Administrator@qq.com
 * Create Time: 2019/03/29
 */
class TaskController extends BaseController
{

    private $service;
    const EVENT_CANCEL_ORDER = 'cancel_order';

    public function init()
    {
        parent::init();
        $this->service = new TaskTaskService();
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => ApiAuth::class,
            'optional' => ['refresh-config', 'get-task-page', 'closed-pink']
        ];
        return $behaviors;
    }
    /*********************Category模块控制层************************************/
    /**
     * 完结拼团
     * @return mixed
     * @throws
     */
    public function actionClosedPink()
    {
        $pinkList = PinkModel::find()->select(['id', 'user_name', 'total_num', 'product_snap'])->where(['status' => StatusEnum::STATUS_INIT, 'is_effect' => StatusEnum::ENABLED,])
            ->andWhere(['<', 'currency_num', new Expression('`total_num`')])
            ->andWhere(['<', 'end_time', time()])->all();

        if (count($pinkList)) {
            foreach ($pinkList as $k => $pink) {
                PinkModel::updateAll(['status' => StatusEnum::EXPIRE], ['id' => $pink['id']]);
                $productName = json_decode($pink['product_snap'])['name'];
                $pinkNumber = $pink['total_num'];
                $leader = $pink['user_name'];
                $orderList = PinkPartakeModel::find()
                    ->select(['app_open_id', 'order_id'])
                    ->where(['status' => StatusEnum::ENABLED, 'pink_id' => $pink['id']])
                    ->all();
                foreach ($orderList as $m => $parTake) {
                    SendWxMsgService::pinkResultMessage(
                        $parTake['app_open_id'], $productName, $pinkNumber, $leader, StatusEnum::FAIL);
                }
                $orderIds = array_column($orderList, 'order_id');
                $job = new RefundOrderJob($orderIds);
                Yii::$app->queue->push($job);
            }

        }
        return count($pinkList);
    }

    /**
     * 分页获取任务列表
     * @return mixed
     * @throws
     */
    public function actionGetTaskPage()
    {

        $query = new TaskTaskQuery();
        $query->setAttributes($this->getRequestGet());
        if ($query->validate()) {
            return $this->service->getTaskTaskPage($query);
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, parent::getModelError($query));
        }
    }

    /**
     * 更新任务列表
     * @return mixed
     * @throws
     */
    public function actionUpdateTaskById()
    {
        $model = new TaskTaskModel(['scenario' => 'update']);
        $model->setAttributes($this->getRequestPost());
        if ($model->validate()) {
            $result = $this->service->updateTaskTaskById($model);
            if ($result) {
                //  $this->runTask(strtoupper(substr(PHP_OS, 0, 3)) === 'WIN');
            }
            return $result;
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, parent::getModelError($model));
        }
    }

    private function runTask($isWindows = true)
    {
        if ($isWindows) {
            $attachment = Yii::getAlias('@attachment') . '/wingocron/';
            passthru($attachment . "run.bat");
            passthru($attachment . "gocron-node.exe");
            passthru($attachment . "gocron.exe web");
        } else {
            $attachment = Yii::getAlias('@attachment') . '/linuxgocron/';
            system('cd ' . $attachment);

            system('./gocron-node');
            exit;
        }
    }

    /**
     *
     * 刷新页面装修配置
     */
    public function actionRefreshConfig()
    {
        $list = PagConfigModel::findAll(['status' => StatusEnum::ENABLED, 'refresh_flag' => StatusEnum::ENABLED]);
        if (count($list)) {
            $forArray=['goodsList','fightGroups','newPeopleExclusive', 'seckill'];
            foreach ($list as $key => $value) {
                if(!empty($value->content)){
                $contList = ArrayHelper::toArray(json_decode($value->content, true));
                foreach ($contList as $k => $v) {
                    if (isset($v['fresh_flag']) && ($v['fresh_flag'] == 1)) {
                        //商品需要刷新
                        if (in_array($v['key'],$forArray)  )  {
                                $productList = $v['list'];
                                if(count($productList)){
                                 $i=0;  $resultList=[];
                                foreach ($productList as $p => $info) {
                                    $product = ProductModel::findOne($info['id']);
                                    if(!empty($product)&& !empty($product->name)){
                                    $info['images'] =  json_decode($product->images);
                                    $info['cover_img'] = $product->cover_img;
                                    $info['sale_price'] = $product->sale_price;
                                    $info['origin_price'] = $product->origin_price;
                                    $info['member_price'] = $product->member_price;
                                    $info['name'] = $product->name;
                                    $info['sales_number'] = $product->sales_number;
                                    $info['sale_strategy'] = $product->sale_strategy;
                                    $info['is_on_sale'] = $product->is_on_sale;
                                    $info['need_score'] = $product->need_score;
                                    $skuStock=  ProductSkuModel::find()->select(['stock_number'=>'SUM(stock_number)'])->where(['product_id'=>$info['id']])->one();
                                    $info['stock_number'] = is_null($skuStock)?0:$skuStock['stock_number'];
                                    $resultList[$i] = $info;
                                    $i++;
                                    }
                                }
                                $v['list']= $resultList;
                                }
                            }
                    }
                    $contList[$k] = $v;
                }
                PagConfigModel::updateAll(['content' => json_encode($contList, JSON_UNESCAPED_UNICODE)], ['id' => $value['id']]);
                }
            }
            return count($list);
        }
    }
}
/**********************End Of Category 控制层************************************/ 
 


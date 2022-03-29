<?php

namespace api\modules\seller\controllers;

use api\modules\auth\ApiAuth;
use api\modules\seller\models\forms\BasicOrderInfoModel;
use api\modules\seller\models\forms\UserInfoModel;
use api\modules\seller\service\ChannelService;
use api\modules\seller\models\forms\ChannelModel;
use api\modules\seller\models\forms\ChannelQuery;
use fanyou\error\FanYouHttpException;
use fanyou\enums\HttpErrorEnum;
use Yii;
use yii\web\HttpException;

/**
 * Class ChannelController
 * @package api\modules\seller\controllers
 * @author: HenryJia
 * @E-mail: 316560925@qq.com
 * @date: 2020-11-16
 */
class ChannelController extends BaseController
{

    private $service;

    public function init()
    {
        parent::init();
        $this->service = new ChannelService();
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => ApiAuth::class,
        ];
        return $behaviors;
    }
    /*********************Channel模块控制层************************************/
    /**
     * 添加
     * @return mixed
     * @throws HttpException
     */
    public function actionAdd()
    {
        $model = new ChannelModel();
        $model->setAttributes($this->getRequestPost());
        if ($model->validate()) {
            if (isset($model->full_url)) {
                if (strpos($model->full_url, '?') !== false) {
                    $model->full_url .= "&sourceId=" . (ChannelModel::find()->count() + 1);
                } else {
                    $model->full_url .= "?sourceId=" . (ChannelModel::find()->count() + 1);
                }
            }

            return $this->service->addChannel($model);
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, parent::getModelError($model));
        }
    }

    /**
     * 分页获取列表
     * @return mixed
     * @throws HttpException
     */
    public function actionGetPage()
    {
        $query = new ChannelQuery();
        $query->setAttributes($this->getRequestGet());
        if ($query->validate()) {
            $result = $this->service->getChannelPage($query);
            if (count($result['list'])) {
                foreach ($result['list'] as $k => $channel) {

                    //会员数量
                    $result['list'][$k]['people'] = UserInfoModel::find()->where(['source_id' => $channel['id']])->count();

                    //订单总数  or  发起数
                    $result['list'][$k]['orderNumber'] = BasicOrderInfoModel::find()->where(['source_id' => $channel['id']])->count();

                   //支付订单数  or  付款数
                    $result['list'][$k]['orderPayNumber'] = BasicOrderInfoModel::find()->where(['source_id' => $channel['id']])->andWhere(['in', 'status', ['closed','unsend','sending']])->count();

                    //支付金额  or  付款金额
                    $result['list'][$k]['orderPayAmount'] = BasicOrderInfoModel::find()->where(['source_id' => $channel['id']])->andWhere(['in', 'status', ['closed','unsend','sending']])->sum('pay_amount');

                   //订单金额  or  总金额
                    $result['list'][$k]['orderAmount'] = BasicOrderInfoModel::find()->where(['source_id' => $channel['id']])->sum('pay_amount');

                    //发起率  发起数/下单数
                  if($result['list'][$k]['orderNumber'] && $channel['place_order']){
                    $result['list'][$k]['orderNumberRatio'] =  round($result['list'][$k]['orderNumber']/$channel['place_order'],2)*100;
                  }else{
                    $result['list'][$k]['orderNumberRatio'] = 0;
                  }

                    // 支付成功率  付款数/发起数
                  if($result['list'][$k]['orderPayNumber'] && $result['list'][$k]['orderNumber']){
                    $result['list'][$k]['orderSuccessrRatio'] = round($result['list'][$k]['orderPayNumber']/$result['list'][$k]['orderNumber'],2)*100;
                  }else{
                    $result['list'][$k]['orderSuccessrRatio'] = 0;
                  }

                    //  客单价
                  if($result['list'][$k]['orderPayAmount'] && $result['list'][$k]['orderPayNumber']){
                    $result['list'][$k]['userUnitPrice'] = round($result['list'][$k]['orderPayAmount']/$result['list'][$k]['orderPayNumber'],2);
                  }else{
                    $result['list'][$k]['userUnitPrice'] = 0;
                  }

                    //  付款率  付款数/下单数
                  if($result['list'][$k]['orderPayNumber'] && $channel['place_order']){
                    $result['list'][$k]['payRatio'] = round($result['list'][$k]['orderPayNumber']/$channel['place_order'],2)*100;
                  }else{
                    $result['list'][$k]['payRatio'] = 0;
                  }

                  $result['list'][$k]['share_url'] = $channel['full_url'].'/pages/tabbar/home/index?sourceId='.$channel['id'];

                }
            }
            return $result;
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, parent::getModelError($query));
        }
    }

    public function actionGetList()
    {
        $query = new ChannelQuery();
        $query->setAttributes($this->getRequestGet());
        if ($query->validate()) {
            $result = $this->service->getList($query);
            return $result;
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, parent::getModelError($query));
        }
    }
    /**
     * 根据Id获取详情
     * @return mixed
     * @throws HttpException
     */
    public function actionGetById()
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
        $model = new ChannelModel(['scenario' => 'update']);
        $model->setAttributes($this->getRequestPost(false));
        if ($model->validate()) {
            if (isset($model->full_url)) {
                if (strpos($model->full_url, 'sourceId=') === false) {
                    if (strpos($model->full_url, '?') !== false) {
                        $model->full_url .= "&sourceId=" . $model->id;
                    } else {
                        $model->full_url .= "?sourceId=" . $model->id;
                    }
                }
            }
            return $this->service->updateChannelById($model);
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, parent::getModelError($model));
        }
    }
    /**
     * 根据Id删除
     * @return mixed
     * @throws HttpException
     */
    public function actionDelById()
    {

        return $this->service->deleteById(parent::getRequestId());
    }
}
/**********************End Of Channel 控制层************************************/ 



<?php

namespace api\modules\manage\service;

use api\modules\manage\models\CacheManage;
use api\modules\manage\models\forms\UserCheckCodeModel;
use api\modules\manage\models\forms\UserCheckCodeQuery;
use api\modules\manage\models\forms\UserCheckCodeRecordModel;
use api\modules\seller\models\forms\BasicOrderInfoModel;
use fanyou\tools\ArrayHelper;
use fanyou\enums\entity\OrderStatusEnum;
use fanyou\enums\HttpErrorEnum;
use fanyou\enums\QueryEnum;
use fanyou\enums\StatusEnum;
use fanyou\error\errorCode\ErrorCheckCode;
use fanyou\error\FanYouHttpException;
use fanyou\models\base\SearchModel;
use yii\db\Expression;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-03-24
 */
class UserCheckCodeService
{

    /*********************UserCoupon模块服务层************************************/

    /**
     * 根据Id更新用户核销券
     * @param UserCheckCodeModel $model
     * @return int
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function updateUserCheckCodeById(UserCheckCodeModel $model): int
    {
        $model->setOldAttribute('id', $model->id);
        return $model->update();
    }


    /**
     * 添加核销记录
     * @param UserCheckCodeRecordModel $model
     * @return mixed
     * @throws \Throwable
     */
    public function addRecord(UserCheckCodeRecordModel $model)
    {
        $old = $this->getOneByBarCode($model->bar_qrcode);
        if ($old) {
            return $old->id;
        }
        $model->insert();
        return $model->getPrimaryKey();
    }

    /**
     * 分页获取核销记录列表
     * @param UserCheckCodeQuery $query
     * @return array
     * @throws \yii\web\NotFoundHttpException
     */
    public function getRecordPage(UserCheckCodeQuery $query): array
    {
        $searchModel = new SearchModel([
            'model' => UserCheckCodeRecordModel::class,
            'scenario' => 'default',
            'partialMatchAttributes' => ['coupon_name', 'order_id', 'user_code'], // 模糊查询
            'defaultOrder' => [
                'created_at' => SORT_DESC
            ],
            'pageSize' => $query->limit,
        ]);
        $searchWord = $searchModel->search($query->toArray([], [QueryEnum::LIMIT]));
        $result['list'] = ArrayHelper::toArray($searchWord->getModels());
        $result['totalCount'] = $searchWord->pagination->totalCount;
        return $result;
    }
    /**
     * 获取核销记录详情
     * @param $id
     * @return UserCheckCodeRecordModel|null
     */
    public function getRecordById($id): ?UserCheckCodeRecordModel
    {
        return UserCheckCodeRecordModel::findOne($id);
    }
    /**
     * 根据barCode获取用户核销券
     * @param $barCode
     * @return UserCheckCodeModel
     */
    public function getOneByBarCode($barCode): ?UserCheckCodeModel
    {
        return UserCheckCodeModel::findOne(['bar_qrcode' => $barCode]);
    }

    /**
     * 根据订单号获取用户核销券
     * @param $orderId
     * @return UserCheckCodeModel|null
     */
    public function getOneByBarOrder($orderId): ?UserCheckCodeModel
    {
        return UserCheckCodeModel::findOne(['order_id' => $orderId]);
    }


    /**
     *  确认核销
     * @param $id
     * @param int $number
     * @param CacheManage $array
     * @return int
     * @throws FanYouHttpException
     * @throws \Throwable
     */
    public function verifyBarCode($id,CacheManage $array,$number=1  )
    {
        $info =  UserCheckCodeModel::findOne( $id );

        if ($info->check_shop_id != $array->shopId) {
            throw  new FanYouHttpException(HttpErrorEnum::Expectation_Failed, ErrorCheckCode::NO_CHECK_POWER);
        }
        if ($info->status != StatusEnum::STATUS_INIT) {
            throw  new FanYouHttpException(HttpErrorEnum::Expectation_Failed, ErrorCheckCode::CODE_UN_EFFECT);
        }
        if ($info->left_number <= 0) {
            throw  new FanYouHttpException(HttpErrorEnum::Expectation_Failed, ErrorCheckCode::CODE_UN_LIMIT);
        }

        if (isset($info->expire_time) && $info->expire_time < time()) {
            throw  new FanYouHttpException(HttpErrorEnum::Expectation_Failed, ErrorCheckCode::CODE_HAD_EXPIRE_TIME);
        }
        return $this->realVerify($info,  $array,$number);
    }

    /**
     * 核销逻辑
     * @param UserCheckCodeModel $info
     * @param CacheManage $array
     * @param int $number
     * @return int
     * @throws \Throwable
     */
    protected function realVerify(UserCheckCodeModel $info, CacheManage $array, $number = 1)
    {
        $orderInfo=$info->orderInfo;

        $model = new UserCheckCodeRecordModel();
        $model->check_source='MOBILE';
        $model->title = $info->title;
        $model->merchant_id = $orderInfo['merchant_id'];
        $model->product_snap = $orderInfo['cart_snap'];
      //  $model->user_snap=$orderInfo['user_snap'];

        $model->order_id=$info->order_id;
        $model->number = $number;
        $model->type = $info->coupon_type;
        $model->bar_qrcode = $info->bar_qrcode;
        $model->check_shop_id = $info->check_shop_id;
        $model->user_id = $info->user_id;


        $model->check_employee_id = $array->id;
        $model->check_employee_name = $array->name;

        if ($model->insert()) {
            if ($info->left_number == $number) {
                return $this->verifyRecordStatus($info->id,$number,$info->bar_qrcode,$info->order_id);
            }
            return $this->verifyRecordStatus($info->id,$number, $info->bar_qrcode,null,false);
        }
    }

    /**
     * 更新核销后状态
     * @param $id
     * @param int $number
     * @param $barQrCode
     * @param bool $verifyAll
     * @param $orderId
     * @return int
     */
    protected function verifyRecordStatus($id,$number=1,$barQrCode,$orderId,$verifyAll = true)
    {
        if ($verifyAll) {
            BasicOrderInfoModel::updateAll(['status'=>OrderStatusEnum::CLOSED,
                'show_bar_qrcode'=>StatusEnum::USED.'_'.$barQrCode],['id'=>$orderId]);
            return UserCheckCodeModel::updateAll(['used_number' => new Expression('used_number+  ' . $number),
                    'left_number' => new Expression('left_number- ' . $number),
                    'bar_qrcode'=>StatusEnum::USED.'_'.$barQrCode,
                    'status' => StatusEnum::USED ]
                , ['and', ['id' => $id],['>=', 'left_number', $number]]
            );


        } else {
            return UserCheckCodeModel::updateAll(['used_number' => new Expression('used_number+  ' . $number),
                    'left_number' => new Expression('left_number- ' . $number)]
                    , ['id' => $id ]);
        }
    }
}
/**********************End Of UserCoupon 服务层************************************/


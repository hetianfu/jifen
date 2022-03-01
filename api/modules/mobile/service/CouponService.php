<?php

namespace api\modules\mobile\service;

use api\modules\mobile\models\forms\CouponModel;
use api\modules\mobile\models\forms\CouponUserModel;
use api\modules\mobile\models\forms\CouponUserQuery;
use fanyou\enums\HttpErrorEnum;
use fanyou\enums\QueryEnum;
use fanyou\enums\StatusEnum;
use fanyou\error\errorCode\ErrorOrder;
use fanyou\error\FanYouHttpException;
use fanyou\models\base\SearchModel;
use fanyou\tools\ArrayHelper;
use yii\db\Expression;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-04-23
 */
class CouponService extends BasicService
{

    /*********************Coupon模块服务层************************************/
    /**
     * 卡券数量减
     * @param $id
     * @param int $number
     * @return int
     */
    public function minusCouponNumberById($id, $number = 1)
    {
        return CouponModel::updateAll(['left_number' => new Expression('`left_number` - ' . $number)], ['and', ['id' => $id], ['>', 'left_number', 0]]);
    }

    /**
     * 取当前卡券详情
     * @param $id
     * @return CouponModel|null
     */
    public function getOneCoupon($id): ?CouponModel
    {
        return CouponModel::findOne($id);
    }

    /**
     * 获取用户可以领取的有效的卡券
     * @param $id
     * @param $userId
     * @return CouponUserModel|null
     * @throws FanYouHttpException
     * @throws \Throwable
     */
    public function getEffectOneCoupon($id, $userId): ?CouponUserModel
    {
        $couponUser = new CouponUserModel();
        $model = CouponModel::find()
           // ->where(['is_limit' => StatusEnum::DISABLED])
            ->where(['>', 'left_number', StatusEnum::STATUS_INIT])
            ->andWhere(['`rf_coupon`.`status`' => StatusEnum::ENABLED])
            ->andWhere(['`rf_coupon`.`id`' => $id])
            ->joinWith('template')
            ->one();
        //当前卡券状态为可用
        if (empty($model)) {

            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, ErrorOrder::COUPON_UN_EFFECT);
        }
        //当前只可领取一次
        if ($model['is_once'] == StatusEnum::ENABLED) {
            if ($this->countCouponUser($model['id'], $userId)) {
                throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, ErrorOrder::COUPON_HAS_REPEAT);
            }
        }
        //当前卡券是否可以永久领取
        if ($model['is_permanent'] == StatusEnum::DISABLED && $model['totime'] < time()) {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, ErrorOrder::COUPON_HAS_OUT_TIME);
        }

        $couponUser->title = $model['template']['title'];
        $couponUser->type = $model['template']['type'];
        $couponUser->amount = $model['template']['amount'];
        $couponUser->limit_amount = $model['template']['limit_amount'];
        $couponUser->merchant_id = $model['template']['merchant_id'];
        $couponUser->type_relation_id = $model['template']['type_relation_id'];
        $couponUser->fromtime = time();
        $couponUser->totime = $couponUser->fromtime + $model['template']['effect_days'] * 24 * 3600;
        $couponUser->editor = $model['editor'];
        $couponUser->coupon_id = $model['id'];
        $couponUser->user_id = $userId;
        $couponUser->status=StatusEnum::STATUS_INIT;
        return $couponUser;
    }

    /**
     *  当前用户可领取的优惠券
     * @param CouponUserQuery $query
     * @return array
     * @throws \yii\web\NotFoundHttpException
     */
    public function getCouponPage(CouponUserQuery $query): array
    {
        $userId = $query->user_id;
        unset($query['user_id']);
        $searchModel = new SearchModel([
            'model' => CouponModel::class,
            'scenario' => 'default',
            'defaultOrder' => [
                'created_at' => SORT_DESC
            ],
            'relations'=>['template'],
            'pageSize' => $query->limit,
        ]);
        $searchWord = $searchModel->search($query->toArray([], [QueryEnum::LIMIT]));
        $searchWord->query->where(['is_permanent'=>StatusEnum::ENABLED])->orWhere(['>','totime',time()]);
        $list = $searchWord->getModels();
        $resultList=[];
        if (count($list)) {
            foreach ($list as $k => $v) {
                //如果限制只能领取一次  ,is_once用户是否可领取
                    if (!( $this->countCouponUser($v['id'], $userId) || empty($v['left_number']))){
                        $resultList[]=$v;
                    }
            }
        }
        $result['list'] = ArrayHelper::toArray($resultList);

        $result['totalCount'] = $searchWord->pagination->totalCount;
        return $result;
    }

    /**
     * 可领导的优惠券列表
     * @param CouponUserQuery $query
     * @return array
     * @throws \yii\web\NotFoundHttpException
     */
    public function getCouldGetCouponPage(CouponUserQuery $query): array
    {
        $userId = $query->user_id;
        unset($query['user_id']);
        $searchModel = new SearchModel([
            'model' => CouponModel::class,
            'scenario' => 'default',
            'defaultOrder' => [
                'created_at' => SORT_DESC
            ],
            'relations'=>['template'],
            'pageSize' => $query->limit,
        ]);
        $searchWord = $searchModel->search($query->toArray([], [QueryEnum::LIMIT]));
        $list =  $searchWord->query->where(['is_permanent'=>StatusEnum::ENABLED])
            ->orWhere(['>','totime',time()])
            ->andWhere(['`rf_coupon`.status'=>1,'is_once'=>1])->all();
        $resultList=[];
        if (count($list)) {
            foreach ($list as $k => $v) {
                //如果限制只能领取一次  ,is_once用户是否可领取
                if (!( $this->countCouponUser($v['id'], $userId) || empty($v['left_number']))){
                    $resultList[]=$v;
                }
            }
        }
        $result['list'] = ArrayHelper::toArray($resultList);

        $result['totalCount'] = $searchWord->pagination->totalCount;
        return $result;
    }
    /**
     * 当前用户已领取的优惠券列表
     * @param CouponUserQuery $query
     * @return array
     * @throws \yii\web\NotFoundHttpException
     */
    public function getCouponUserPage(CouponUserQuery $query): array
    {
        $searchModel = new SearchModel([
            'model' => CouponUserModel::class,
            'scenario' => 'default',
            'defaultOrder' => [
                'totime' => SORT_DESC
            ],
            'pageSize' => $query->limit,
        ]);
        $searchWord = $searchModel->search($query->toArray([], [QueryEnum::LIMIT]));
        $list = $searchWord->getModels();
        if (count($list)) {
            $now= time();
            foreach ($list as $k => $v) {
                if ($v['status'] == StatusEnum::STATUS_INIT) {
                    if ($v['totime'] < $now) {
                        $list[$k]['status'] = StatusEnum::DELETE;
                        $this->disableExpireCoupon($v);
                    }
                }
            }
        }
        $result['list'] = ArrayHelper::toArray($list);
        $result['totalCount'] = $searchWord->pagination->totalCount;
        return $result;
    }


    /**
     * 删除用户优惠券
     * @param $id
     * @return int
     */
    public function delCouponUserById($id)
    {
        return CouponUserModel::updateAll(['is_del' => StatusEnum::ENABLED], ['id' => $id]);
    }

    /**
     * 统计用户领取次数
     * @param $couponId
     * @param $userId
     * @return int|string
     */
    public function countCouponUser($couponId, $userId)
    {
        return CouponUserModel::find()->where(['coupon_id' => $couponId, 'user_id' => $userId])->count();
    }

    /**
     * 过期卡券失效
     * @param CouponUserModel $model
     * @return int|string
     */
    public function disableExpireCoupon(CouponUserModel $model)
    {
        return CouponUserModel::updateAll(['status' => StatusEnum::DELETE], ['id' => $model->id]);
    }


}
/**********************End Of Coupon 服务层************************************/


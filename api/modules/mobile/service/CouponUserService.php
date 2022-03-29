<?php

namespace api\modules\mobile\service;

use api\modules\mobile\models\forms\CouponUserModel;
use api\modules\mobile\models\forms\CouponUserQuery;
use fanyou\enums\QueryEnum;
use fanyou\enums\StatusEnum;
use fanyou\tools\ArrayHelper;
use fanyou\models\base\SearchModel;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-04-23
 */
class CouponUserService extends  BasicService
{

    /*********************CouponUser模块服务层************************************/
    /**
     * 添加一条优惠券领取记录
     * @param CouponUserModel $model
     * @return mixed
     * @throws \Throwable
     */
    public function addCouponUser(CouponUserModel $model)
    {
        $model->insert();
        return $model->getPrimaryKey();
    }



    /**
     * 分页获取列表
     * @param CouponUserQuery $query
     * @return array
     * @throws \yii\web\NotFoundHttpException
     */
    public function getCouponUserPage(CouponUserQuery $query): array
    {
        $searchModel = new SearchModel([
            'model' => CouponUserModel::class,
            'scenario' => 'default',
            'partialMatchAttributes' => ['name'], // 模糊查询
            'defaultOrder' => [
                'created_at' => SORT_DESC
            ],
            'pageSize' => $query->limit,
        ]);
        $searchWord = $searchModel->search( $query->toArray([],[QueryEnum::LIMIT]) );
        $result['list'] = ArrayHelper::toArray($searchWord->getModels());
        $result['totalCount'] = $searchWord->pagination->totalCount;
        return $result;
    }

    /**
     * 获取用户可以使用的优惠券
     * @param $userId
     * @return array
     * @throws \yii\web\NotFoundHttpException
     */
    public function getCanUsedCouponList( $userId): array
    {   $query=new CouponUserQuery();
        $query->user_id=$userId;
        $query->status=StatusEnum::UN_USED;
        $searchModel = new SearchModel([
            'model' => CouponUserModel::class,
            'scenario' => 'default',
            'partialMatchAttributes' => ['coupon_name', 'order_id', 'user_code'], // 模糊查询
            'defaultOrder' => [
                'created_at' => SORT_DESC
            ],
        ]);
        $searchWord = $searchModel->search( $query->toArray() );
        return ArrayHelper::toArray($searchWord->getModels());
    }

    /**
     * 根据Id获取领取记录
     * @param $id
     * @return Object
     */
    public function getOneById($id)
    {
        return CouponUserModel::findOne($id);
    }

    /**
     * 根据Id更新领取记录
     * @param CouponUserModel $model
     * @return int
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function updateCouponUserById(CouponUserModel $model): int
    {
        $model->setOldAttribute('id', $model->id);
        return $model->update();
    }

    /**
     * 根据Id删除领取记录
     * @param $id
     * @return int
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function deleteById($id): int
    {
        $model = CouponUserModel::findOne($id);
        return $model->delete();
    }

    /**
     * 获取卡券价格
     * @param $couponUserId
     * @param $amount
     * @return int
     */
    public function getCouponPrice($couponUserId,$amount): float
    {
        $result = 0;
        $model = CouponUserModel::findOne($couponUserId);
        if ($model->status ===  StatusEnum::UN_USED) {
             if(empty($model->limit_amount) || ( $amount -$model->limit_amount )>=0  ){
                $result= $model->amount;
             }
        }
        return  $result ;
    }
}
/**********************End Of CouponUser 服务层************************************/


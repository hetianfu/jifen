<?php

namespace api\modules\mobile\service;

use api\modules\mobile\models\forms\PinkConfigModel;
use api\modules\mobile\models\forms\PinkModel;
use api\modules\mobile\models\forms\PinkPartakeModel;
use api\modules\mobile\models\forms\PinkPartakeQuery;
use api\modules\mobile\models\forms\PinkQuery;
use fanyou\enums\HttpErrorEnum;
use fanyou\enums\QueryEnum;
use fanyou\enums\StatusEnum;
use fanyou\error\FanYouHttpException;
use fanyou\tools\ArrayHelper;
use fanyou\models\base\SearchModel;
use yii\db\Expression;

/**
 * Class PinkService
 * @package api\modules\seller\service
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-08-20
 */
class PinkService
{
    private $configService;

    public function init()
    {
        $this->configService = new PinkConfigService();
    }
/*********************Pink模块服务层************************************/
    /**
     * 添加一条拼团
     * @param $productId
     * @param $userId
     * @return mixed
     * @throws FanYouHttpException
     * @throws \Throwable
     */
	public function addPinkByProductId($productId,$userId)
	{
        $pinkConfig = PinkConfigModel::findOne(['product_id'=>$productId]);
        if (empty($pinkConfig) || $pinkConfig['end_time'] <= time()) {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, '拼团已结束');
        }
        $model = new PinkModel();
        $model->status = 0;
        $model->currency_num = 1;
        $model->user_id = $userId;
        $model->total_num = $pinkConfig['people'];
        $model->product_id = $pinkConfig['product_id'];
        $model->end_time = time() + $pinkConfig['remain_time'];
		$model->insert();
		return $model->getPrimaryKey();
	}
    public function partToPink($id,$number=1)
    {
        return PinkModel::updateAll(['currency_num' => new Expression('`currency_num` + ' . $number)], ['id' => $id]);
    }

    /**
     * 获取当前正在进行中的拼团
     * @param $productId
     * @return array
     * @throws \yii\web\NotFoundHttpException
     */
    public function getPinkListByProductId($productId): array
    {
        $query = new  PinkQuery();
        $query->is_effect=StatusEnum::ENABLED;
        $query->status=StatusEnum::STATUS_INIT;
        $query->product_id = $productId;
        $query->end_time=QueryEnum::GT.time();
        $searchModel = new SearchModel([
            'model' => PinkModel::class,
            'scenario' => 'default',
            'partialMatchAttributes' => ['name'], // 模糊查询
            'defaultOrder' => $query->getOrdersArray(),
            'pageSize' => $query->limit,
        ]);
        $searchWord = $searchModel->search($query->toArray());

        return ArrayHelper::toArray($searchWord->getModels());
    }
	/**
	 * 分页获取列表
	 * @param PinkQuery $query
	 * @return array
	 * @throws \yii\web\NotFoundHttpException
	 */
	public function getPinkPage(PinkQuery $query): array
	{
		$searchModel = new SearchModel([
			'model' => PinkModel::class,
			'scenario' => 'default',
			'partialMatchAttributes' => ['name'], // 模糊查询
			'defaultOrder' => $query->getOrdersArray(),
			'pageSize' => $query->limit,
		]);
		$searchWord = $searchModel->search( $query->toArray([],[QueryEnum::LIMIT]) );
		$result['list'] = ArrayHelper::toArray($searchWord->getModels());
		$result['totalCount'] = $searchWord->pagination->totalCount;
		return $result;
	}

	/**
	 * 根据Id获取拼团
	 * @param $id
	 * @return Object
	 */
	public function getOneById($id)
	{
		return PinkModel::findOne($id);
	}

    /**
     * 获取用户当前参与的拼团
     * @param $id
     * @param $productId
     * @return PinkModel|null
     */
    public function getOneByProductId($id,$productId)
    {
        return PinkModel::findOne(['status'=>StatusEnum::STATUS_INIT,'user_id'=>$id,'product_id'=>$productId]);
    }
    /**
     * 根据商品获取拼团信息
     * @param $productId
     * @param $userId
     * @return array
     */
    public function getByProductId($productId,$userId)
    {
        $array=[];
        //取所有进行中的拼团信息
        $pinkList = PinkModel::find()->where(['product_id'=>$productId,'status'=>0])->andWhere(['>','end_time',time()])->all();
        //获取
        if(count($pinkList)) {
            foreach ($pinkList as $k => $v) {
                $partakeList = PinkPartakeModel::find()->where(['pink_id' => $v['id'], 'status' => 1])->all();
                $pinkList[$k]['partakeList'] = $partakeList;
                $userIds = array_column($partakeList, 'user_id');
                if (in_array($userId, $userIds)) {
                    $array['myTake'] = PinkPartakeModel::findOne(['user_id' => $userId, 'pink_id' => $v['id'], 'status' => 1]);
                }
            }
            $array['list'] = $pinkList;
        }
        return $array;
    }



	/**
	 * 根据Id更新拼团
	 * @param PinkModel $model
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function updatePinkById (PinkModel $model): int
	{
		$model->setOldAttribute('id',$model->id);
		return $model->update();
	}

    /**
     * 根据Id获取拼团
     * @param $orderId
     * @return Object
     */
    public function getPinkIdByOrderId($orderId)
    {
        $model=PinkPartakeModel::findOne(['order_id'=>$orderId]);
        return empty($model)?null:$model->pink_id;
    }

    /**
     * 添加一条参团人记录
     * @param PinkPartakeModel $model
     * @return mixed
     * @throws \Throwable
     */
    public function addPinkPartake(PinkPartakeModel $model)
    {
        $model->insert();
        return $model->getPrimaryKey();
    }

    /**
     * 获取单条参团信息
     * @param PinkPartakeQuery $query
     * @return array
     * @throws \yii\web\NotFoundHttpException
     */
    public function getOnePartake(PinkPartakeQuery $query)
    {
        $searchModel = new SearchModel([
            'model' => PinkPartakeModel::class,
            'scenario' => 'default',
            'partialMatchAttributes' => ['name'], // 模糊查询
            'defaultOrder' => $query->getOrdersArray(),
        ]);
        $searchWord = $searchModel->search($query->toArray());

        return $searchWord->query->one() ;
    }
    public function getPartakeList(PinkPartakeQuery $query): array
    {
        $searchModel = new SearchModel([
            'model' => PinkPartakeModel::class,
            'scenario' => 'default',
            'partialMatchAttributes' => ['name'], // 模糊查询
            'defaultOrder' => $query->getOrdersArray(),
        ]);
        $searchWord = $searchModel->search( $query->toArray() );
        return ArrayHelper::toArray($searchWord->getModels());
    }

}
/**********************End Of Pink 服务层************************************/


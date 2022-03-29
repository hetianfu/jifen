<?php

namespace api\modules\seller\service;

use api\modules\seller\models\forms\CouponModel;
use api\modules\seller\models\forms\CouponQuery;
use api\modules\seller\models\forms\CouponTemplateModel;
use api\modules\seller\models\forms\CouponTemplateQuery;
use api\modules\seller\models\forms\CouponUserModel;
use fanyou\enums\StatusEnum;
use fanyou\tools\ArrayHelper;
use fanyou\models\base\SearchModel;
use fanyou\enums\QueryEnum;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-04-23
 */
class CouponService extends BasicService
{

/*********************Coupon模块服务层************************************/
    /**
     * 添加一条优惠券模版
     * @param CouponTemplateModel $model
     * @return mixed
     * @throws \Throwable
     */
    public function addCouponTemplate(CouponTemplateModel $model)
    {
        $model->insert();
        return $model->getPrimaryKey();
    }
    /**
     * 分页获取列表
     * @param CouponTemplateQuery $query
     * @return array
     * @throws \yii\web\NotFoundHttpException
     */
    public function getCouponTemplatePage(CouponTemplateQuery $query): array
    {
        $searchModel = new SearchModel([
            'model' => CouponTemplateModel::class,
            'scenario' => 'default',
            'partialMatchAttributes' => ['title'], // 模糊查询
            'defaultOrder' => $query->getOrdersArray(),
            'pageSize' => $query->limit,
        ]);
        $searchWord = $searchModel->search( $query->toArray([],[QueryEnum::LIMIT])  );
        $result['list'] = ArrayHelper::toArray($searchWord->getModels());

        $result['totalCount'] = $searchWord->pagination->totalCount;
        return $result;
    }
    public function getCouponTemplateList(CouponTemplateQuery $query): array
    {
        $searchModel = new SearchModel([
            'model' => CouponTemplateModel::class,
            'scenario' => 'default',
            'partialMatchAttributes' => ['title'], // 模糊查询
            'defaultOrder' => [
                'created_at' => SORT_DESC
            ],
        ]);
        $searchWord = $searchModel->search(  $query->toArray() );
        return $searchWord->getModels();
    }

    /**
     * 根据Id获取模版
     * @param $id
     * @return Object
     */
    public function getCouponTemplateById($id):CouponTemplateModel
    {
        return CouponTemplateModel::findOne($id);
    }

    /**
     * 根据Id更新模版
     * @param CouponTemplateModel $model
     * @return int
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function updateCouponTemplateById (CouponTemplateModel $model): int
    {
        $model->setOldAttribute('id',$model->id);
        return $model->update();
    }
    /**
     * 根据Id删除模版
     * @param $id
     * @return int
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function deleteTemplateById ($id) : int
    {
        $model = CouponTemplateModel::findOne($id);
        return  $model->delete();
    }

    /**
     * 批量生成优惠券
     * @param CouponModel $model
     * @return mixed
     * @throws \Throwable
     */
//    public function batchAddCoupon( $array)
//    {
//        // 批量写入数据
//        $field = ['title','type','type_relation_id','seller','editor','note', 'addtime','fromtime','totime','is_limit','limit_number','left_number', 'created_at', 'updated_at'];
//        !empty($rows) && Yii::$app->db->createCommand()->batchInsert(CouponModel::tableName(), $field, $rows)->execute();
//        return count($rows);
//    }

      /**
	 * 发布优惠券
	 * @param CouponModel $model
     * @return mixed
     * @throws \Throwable
     */
	public function addCoupon(CouponModel $model)
	{

//        $templateId= $model->template_id;
//        $templateModel= $this->getCouponTemplateById($templateId);
//        $model->type=$templateModel->type;
//        $model->type_relation_id=$templateModel->type_relation_id;
//        $model->title=$templateModel->title;

        $model->insert();
		return $model->getPrimaryKey();
	}
	/**
	 * 分页获取列表
	 * @param CouponQuery $query
	 * @return array
	 * @throws \yii\web\NotFoundHttpException
	 */
	public function getCouponPage(CouponQuery $query): array
	{
		$searchModel = new SearchModel([
			'model' => CouponModel::class,
			'scenario' => 'default',
			'partialMatchAttributes' => ['title'], // 模糊查询
			'defaultOrder' =>$query->getOrdersArray(),
			'pageSize' => $query->limit,
		]);
		$searchWord = $searchModel->search($query->toArray([],[QueryEnum::LIMIT]));
        $result['list'] = ArrayHelper::toArray($searchWord->getModels());
		$result['totalCount'] = $searchWord->pagination->totalCount;
		return $result;
	}

	/**
	 * 根据Id获取
	 * @param $id
	 * @return Object
	 */
	public function getOneById($id)
	{
		return CouponModel::findOne($id);
	}

	/**
	 * 根据Id更新
	 * @param CouponModel $model
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function updateCouponById (CouponModel $model): int
	{
		$model->setOldAttribute('id',$model->id);
		return $model->update();
	}
	/**
	 * 根据Id删除
	 * @param $id
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function deleteById ($id) : int
	{
		$model = CouponModel::findOne($id);
        $result=$model->delete();
        if($result){
         // $this->disableUserCoupon($id);
        }
		return  $result;
	}

    /**
     *  所有用户的卡券失效
     * @param $id
     * @return int
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function disableUserCoupon ($id) : int
    {
        return  CouponUserModel::updateAll(['status'=>StatusEnum::DELETE],['coupon_id'=>$id]);
    }

}
/**********************End Of Coupon 服务层************************************/


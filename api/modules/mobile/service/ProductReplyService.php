<?php

namespace api\modules\mobile\service;

use api\modules\mobile\models\forms\ProductReplyModel;
use api\modules\mobile\models\forms\ProductReplyQuery;
use fanyou\enums\QueryEnum;
use fanyou\enums\SortEnum;
use fanyou\tools\ArrayHelper;
use fanyou\models\base\SearchModel;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-05-27
 */
class ProductReplyService
{

/*********************ProductReply模块服务层************************************/
	/**
	 * 添加一条商品评论表
	 * @param ProductReplyModel $model
     * @return mixed
     * @throws \Throwable
     */
	public function addProductReply(ProductReplyModel $model)
	{
		$model->insert();
		return $model->getPrimaryKey();
	}
	/**
	 * 分页获取列表
	 * @param ProductReplyQuery $query
	 * @return array
	 * @throws \yii\web\NotFoundHttpException
	 */
	public function getProductReplyPage(ProductReplyQuery $query): array
	{
		$searchModel = new SearchModel([
			'model' => ProductReplyModel::class,
			'scenario' => 'default',
			'partialMatchAttributes' => ['name'], // 模糊查询
			'defaultOrder' =>$query->getOrdersArray('updated_at',SORT_DESC),
			'pageSize' => $query->limit,
            'relations'=>['userInfo']
		]);
		$searchWord = $searchModel->search( $query->toArray([],[QueryEnum::LIMIT]) );
		$list = ArrayHelper::toArray($searchWord->getModels());

		foreach ($list as &$row){
      if(!isset($row['userInfo'])){
        $row['userInfo']['nickName'] = $row['user_name'];
      }
    }
    $result['list'] = $list;
		$result['totalCount'] = $searchWord->pagination->totalCount;

		return $result;
	}
    public function getProductReplyList(ProductReplyQuery $query): array
    {
        $searchModel = new SearchModel([
            'model' => ProductReplyModel::class,
            'scenario' => 'default',
            'partialMatchAttributes' => ['name'], // 模糊查询
            'defaultOrder' =>$query->getOrdersArray(SortEnum::SHOW_ORDER),
            'pageSize' => $query->limit,
            'relations'=>['userInfo']
        ]);
        $searchWord = $searchModel->search( $query->toArray() );

        return ArrayHelper::toArray($searchWord->getModels());
    }
	/**
	 * 根据Id获取论表
	 * @param $id
	 * @return Object
	 */
	public function getOneById($id)
	{
		return ProductReplyModel::findOne($id);
	}


	/**
	 * 根据Id更新论表
	 * @param ProductReplyModel $model
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function updateProductReplyById (ProductReplyModel $model): int
	{
		$model->setOldAttribute('id',$model->id);
		return $model->update();
	}
	/**
	 * 根据Id删除论表
	 * @param $id
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function deleteById ($id) : int
	{
		$model = ProductReplyModel::findOne($id);
		return  $model->delete();
	}
}
/**********************End Of ProductReply 服务层************************************/


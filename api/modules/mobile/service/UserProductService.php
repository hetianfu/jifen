<?php

namespace api\modules\mobile\service;

use api\modules\mobile\models\forms\UserProductModel;
use api\modules\mobile\models\forms\UserProductQuery;
use fanyou\enums\QueryEnum;
use fanyou\tools\ArrayHelper;
use fanyou\models\base\SearchModel;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-05-21
 */
class UserProductService extends  BasicService
{

/*********************UserProduct模块服务层************************************/
	/**
	 * 添加一条用户购买商品记录
	 * @param UserProductModel $model
     * @return mixed
     * @throws \Throwable
     */
	public function addUserProduct(UserProductModel $model)
	{
		$model->insert();
		return $model->getPrimaryKey();
	}

    /**
     * 批量添加
     * @param $array
     * @return mixed
     */
    public function batchAddUserProduct(array $array)
    {
        $field = [ 'product_id', 'user_id', 'number', 'product_name', 'status' ,'created_at', 'updated_at'];
        $rows=[];
        $now=time();
        foreach ($array as $key=>$value){
            $v['product_id']=$value['product_id'];
            $v['user_id']=$value['user_id'];
            $v['number']=$value['number'];
            $v['product_name']=$value['product_name'];
            $v['status']=$value['status'];
            $v['created_at']=$now;
            $v['updated_at']=$now;
            ksort($v);
            $rows[]=$v;
        }
        // 批量写入数据
        sort($field);
        !empty($rows) && parent::DbCommand()->batchInsert(UserProductModel::tableName(), $field, $rows)->execute();
        return count($rows);
    }
	/**
	 * 分页获取列表
	 * @param UserProductQuery $query
	 * @return array
	 * @throws \yii\web\NotFoundHttpException
	 */
	public function getUserProductPage(UserProductQuery $query): array
	{
		$searchModel = new SearchModel([
			'model' => UserProductModel::class,
			'scenario' => 'default',
			'partialMatchAttributes' => ['name'], // 模糊查询
			'defaultOrder' => [
			'created_at' => SORT_DESC
			],
			'pageSize' => $query->limit,
		]);
		$searchWord = $searchModel->search(array_filter($query->toArray()));
		$result['list'] = ArrayHelper::toArray($searchWord->getModels());
		$result['totalCount'] = $searchWord->pagination->totalCount;
		return $result;
	}

    /**
     * 联合查询用户基础信息
     * @param UserProductQuery $query
     * @return array
     * @throws \yii\web\NotFoundHttpException
     */
    public function getUserProductWithUserInfoLimit(UserProductQuery $query): array
    {
        $searchModel = new SearchModel([
            'model' => UserProductModel::class,
            'scenario' => 'default',
            'partialMatchAttributes' => ['name'], // 模糊查询
            'defaultOrder' => [
                'created_at' => SORT_DESC
            ],
            'pageSize' => $query->limit,
           // 'relations'=>['userInfo']
        ]);
        $searchWord = $searchModel->search( $query->toArray([],[QueryEnum::LIMIT]) );

        return ArrayHelper::toArray($searchWord->getModels());
    }

	/**
	 * 根据Id获取买商品记录
	 * @param $id
	 * @return Object
	 */
	public function getOneById($id)
	{
		return UserProductModel::findOne($id);
	}

	/**
	 * 根据Id更新买商品记录
	 * @param UserProductModel $model
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function updateUserProductById (UserProductModel $model): int
	{
		$model->setOldAttribute('id',$model->id);
		return $model->update();
	}
	/**
	 * 根据Id删除买商品记录
	 * @param $id
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function deleteById ($id) : int
	{
		$model = UserProductModel::findOne($id);
		return  $model->delete();
	}
}
/**********************End Of UserProduct 服务层************************************/


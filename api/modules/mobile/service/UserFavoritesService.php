<?php

namespace api\modules\mobile\service;

use api\modules\mobile\models\forms\ProductModel;
use api\modules\mobile\models\forms\UserFavoritesModel;
use api\modules\mobile\models\forms\UserFavoritesQuery;
use fanyou\tools\ArrayHelper;
use fanyou\models\base\SearchModel;
use fanyou\enums\entity\SystemGroupEnum;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-05-05
 */
class UserFavoritesService
{
    private $productService;
    public function __construct()
    {
        $this->productService=new ProductService();
    }
/*********************UserFavorites模块服务层************************************/
	/**
	 * 添加一条我的收藏
	 * @param UserFavoritesModel $model
     * @return mixed
     * @throws \Throwable
     */
	public function addUserFavorites(UserFavoritesModel $model)
	{
		$result=$model->insert();
		if($result){
           //添加收藏 addUserFavorites
            switch ($model->favorites_type) {
                case SystemGroupEnum::PRODUCT_TYPE:
                    $p=new ProductModel;
                    $p->id=$model->favorite_id;
                    $this->productService->storeProduct($p);
                   break;
            }
        }

		return $model->getPrimaryKey();
	}
	/**
	 * 分页获取列表
	 * @param UserFavoritesQuery $query
	 * @return array
	 * @throws \yii\web\NotFoundHttpException
	 */
	public function getUserFavoritesPage(UserFavoritesQuery $query): array
	{
		$searchModel = new SearchModel([
			'model' => UserFavoritesModel::class,
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
	 * 根据Id获取藏
	 * @param $id
	 * @return Object
	 */
	public function getOneById($id)
	{
		return UserFavoritesModel::findOne($id);
	}

    /**
     *  根据Id更新收藏
     * @param UserFavoritesModel $model
     * @return int
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
	public function updateUserFavoritesById (UserFavoritesModel $model): int
	{
		$model->setOldAttribute('id',$model->id);
		return $model->update();
	}
	/**
	 * 根据Id删除收藏
	 * @param $id
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function deleteById ($id) : int
	{
		$model = UserFavoritesModel::findOne($id);
            //添加收藏 addUserFavorites
            switch ($model->favorites_type) {
                case SystemGroupEnum::PRODUCT_TYPE:
                    $p=new ProductModel;
                    $p->id=$model->favorite_id;
                    $this->productService->cancelStoreProduct($p);
                    break;
            }
		return  $model->delete();
	}
}
/**********************End Of UserFavorites 服务层************************************/


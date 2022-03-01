<?php

namespace api\modules\seller\service;

use api\modules\seller\models\forms\CategoryModel;
use api\modules\seller\models\forms\CategoryQuery;
use api\modules\seller\models\forms\ProductModel;
use fanyou\enums\HttpErrorEnum;
use fanyou\enums\QueryEnum;
use fanyou\error\errorCode\ErrorCategory;
use fanyou\error\FanYouHttpException;
use fanyou\models\base\SearchModel;
use fanyou\tools\ArrayHelper;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2019/03/29
 */
class CategoryService
{

    /*********************Category模块服务层************************************/
    /**
     * 添加分类
     * @param CategoryModel $model
     * @return string
     * @throws \Throwable
     */
    public function addCategory(CategoryModel $model): string
    {
        $this->verifyRepeatName($model->merchant_id, $model->name);

        $model->insert();
        return $model->getPrimaryKey();
    }

    /**
     * 分页获取对象列表
     * @param CategoryQuery $query dd
     * @return array
     * @throws \yii\web\NotFoundHttpException
     */
    public function getCategoryPage(CategoryQuery $query): array
    {
        $searchModel = new SearchModel([
            'model' => CategoryModel::class,
            'scenario' => 'default',
            'partialMatchAttributes' => ['name'], // 模糊查询
            'defaultOrder' => $query->getOrdersArray(),
            'pageSize' => $query->limit,
        ]);
        $searchWord = $searchModel->search($query->toArray([], [QueryEnum::LIMIT]));
        $getResult = $searchWord->getModels();
        $result['list'] = ArrayHelper::toArray($getResult);
        $result['totalCount'] = $searchWord->pagination->totalCount;
        return $result;
    }

    /**
     * 获取全部列表
     * @param CategoryQuery $query
     * @return array
     * @throws \yii\web\HttpException
     */
    public function getCategoryList(CategoryQuery $query): array
    {
        $searchModel = new SearchModel([
            'model' => CategoryModel::class,
            'scenario' => 'default',
            'partialMatchAttributes' => ['name'], // 模糊查询
            'defaultOrder' => [
                'show_order' => SORT_DESC
            ],
        ]);
        $searchWord = $searchModel->search($query->toArray());
        return ArrayHelper::toArray($searchWord->getModels());
    }

    /**
     * 根据Id获取分类详情
     * @param $id
     * @return CategoryModel
     */
    public function getOneById($id):?CategoryModel
    {
        return CategoryModel::findOne($id);
    }

    /**
     * 更新店铺分类
     * @param CategoryModel $model
     * @return int
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function updateCategory(CategoryModel $model): int
    {
        if(!empty($model->name)){
        $old = $this->getOneById($model->id);
            if (  $model->name !== $old->name) {
                $this->verifyRepeatName($model->merchant_id, $model->name);
            }
        }
        $model->setOldAttribute('id', $model->id);
        return $model->update();
    }

    /**
     * 删除分类
     * @param $id
     * @return int
     * @throws \Throwable
     */
    public function deleteCategory($id): int
    {
        $this->verifyDelete($id);
        $model = CategoryModel::findOne($id);
        return $model->delete();
    }


    /**
     * 获取包括子分类的所有分类
     * @param CategoryQuery $query
     * @param bool $emptyTop 是否包含空的顶级分类
     * @return array
     * @throws \yii\web\NotFoundHttpException
     */
    public function getRecursionCategoryList(CategoryQuery $query,$emptyTop=true): array
    {
        $searchModel = new SearchModel([
            'model' => CategoryModel::class,
            'scenario' => 'default',
            'partialMatchAttributes' => ['name'], // 模糊查询
            'defaultOrder' => [
                'show_order' => SORT_DESC,
            ],
        ]);

        $searchWord = $searchModel->search($query->toArray());
        $array = ArrayHelper::toArray($searchWord->getModels());
        $removed=[];
        foreach ($array as $key => $value) {
            $child=$this->getCategoryListByPid($value->id);
            $array[$key]['child'] = $child;
            if( !$emptyTop   &&  empty($child)){
                $removed[]=$key;
            }
        }
        if(!$emptyTop && !empty($removed)){
         $array= array_diff_key($array,$removed);
        }
        return $array;
    }

    /**
     * 获取当前分类及下级所有分类Id
     * @param $id
     * @return array
     */
    public function getRecursionCategoryById($id)
    {
        $result = $this->getCategoryByPid($id);
        array_push($result, $id);
        return $result;
    }

    public function getCategoryByPid($pid): array
    {
        $result = [];
        $category = CategoryModel::find()->select(['id'])->where(['parent_id' => $pid])->asArray()->column();
        if (!empty($category)) {
            $result = array_merge($result, $category);
            foreach ($category as $key => $value) {
                $result = array_merge($result, $this->getCategoryByPid($value));
            }
        }
        return $result;
    }

    /**
     * 获取当前父分类及下级所有子分类
     * @param $pid
     * @return array
     */
    public function getCategoryListByPid($pid)
    {
        return CategoryModel::find()->select(['id', 'name', 'pic'])->where(['parent_id' => $pid])->asArray()->all();
    }

    /**
     * 较验分类名是否重复
     * @param $merchantId
     * @param $categoryName
     * @return int
     * @throws FanYouHttpException
     */
    public function verifyRepeatName($merchantId, $categoryName): int
    {
        $count = CategoryModel::find()->select(['id'])->where(['name' => $categoryName, 'merchant_id' => $merchantId])->count();
        if ($count) {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, ErrorCategory::NAME_HAS_REPEAT);
        }
        return $count;
    }

    /**
     * 较验分类是否可以删除
     * @param $categoryId
     * @return int
     * @throws FanYouHttpException
     */
    public function verifyDelete($categoryId): int
    {
        $count = CategoryModel::find()->select(['id'])->where(['parent_id' => $categoryId])->count();
        if ($count) {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, ErrorCategory::HAS_SON);
        }
        $count = ProductModel::find()->select(['id'])->where(['category_id' => $categoryId])->count();
        if ($count) {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, ErrorCategory::HAS_PRODUCT);
        }
        return $count;
    }

}
/**********************End Of Category 服务层************************************/ 


<?php

namespace api\modules\mobile\service;

use api\modules\mobile\models\forms\CategoryModel;
use api\modules\mobile\models\forms\CategoryQuery;
use fanyou\models\base\SearchModel;
use fanyou\enums\StatusEnum;
use fanyou\tools\ArrayHelper;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2019/03/29
 */
class CategoryService
{

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
        $searchWord = $searchModel->search(array_filter($query->toArray()));
        return  ArrayHelper::toArray($searchWord->getModels());
    }
    public function getCategoryListWithChild(CategoryQuery $query): array
    {
        $searchModel = new SearchModel([
            'model' => CategoryModel::class,
            'scenario' => 'default',
            'partialMatchAttributes' => ['name'], // 模糊查询
            'defaultOrder' => $query->getOrdersArray('show_order'),

        ]);
        $searchWord = $searchModel->search( $query->toArray() );
        $array=ArrayHelper::toArray($searchWord->getModels());
        foreach ($array as $key =>$value){
            $array[$key]['child']=  $this->getCategoryByPid($value['id']);
        }
        return $array ;
    }

    /**
     * 获取当前父分类及下级所有子分类
     * @param $pid
     * @return array
     */
    public function getCategoryByPid($pid)
    {
        return CategoryModel::find()->select(['id','name','pic'])->where(['parent_id'=>$pid, 'status'=>StatusEnum::ENABLED ] )->asArray()->all();
    }



}
/**********************End Of Category 服务层************************************/ 


<?php

namespace api\modules\seller\service;

use api\modules\seller\models\forms\ShopDepartmentModel;
use api\modules\seller\models\forms\ShopDepartmentQuery;
use fanyou\tools\ArrayHelper;
use yii\data\Pagination;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2019/04/24
 */
class ShopDepartmentService
{
    /*********************ShopDepartment模块服务层************************************/
    /**
     * 添加一个部门
     * @param ShopDepartmentModel $model
     * @return mixed
     * @throws \Throwable
     */
    public function addShopDepartment(ShopDepartmentModel $model)
    {
        $model->insert();
        return $model->getPrimaryKey();
    }

    /**
     * 分页获取店铺部门
     * @param ShopDepartmentQuery $query
     * @return array
     */
    public function getShopDepartmentPage(ShopDepartmentQuery $query): array
    {
        $data = ShopDepartmentModel::find()->filterWhere($query->toArray());
        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => $query->page]);
        $modelList = $data->offset($pages->offset)->limit($query->limit)->all();
        return  ArrayHelper::toArray($modelList);
    }

    /**
     * 获取店铺所有部门
     * @param ShopDepartmentQuery $query
     * @return array
     */
    public function getShopDepartmentList(ShopDepartmentQuery $query): array
    {

        $modelList = ShopDepartmentModel::find()->filterWhere($query->toArray())->all();
        return ArrayHelper::toArray($modelList);;
    }

    /**
     * 根据Id获取部门详情
     * @param $id
     * @return array
     */
    public function getShopDepartmentById($id)
    {
        $shopInfo = ShopDepartmentModel::findOne($id);
        return $shopInfo->toArray();
    }

    /**
     * 更新部门信息
     * @param ShopDepartmentModel $model
     * @return int
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function updateShopDepartment(ShopDepartmentModel $model): int
    {
        $model->setOldAttribute('id',$model->id);
        return $model->update();
    }

    /**
     * 根据Id删除部门
     * @param $id
     * @return int
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function deleteShopDepartment($id): int
    {
        $user = ShopDepartmentModel::findOne($id);
        return  $user->delete();
    }
}
/**********************End Of ShopDepartment 服务层************************************/ 


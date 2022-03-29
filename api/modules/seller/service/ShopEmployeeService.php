<?php

namespace api\modules\seller\service;

use api\modules\seller\models\forms\ShopEmployeeModel;
use api\modules\seller\models\forms\ShopEmployeeQuery;
use fanyou\enums\HttpErrorEnum;
use fanyou\enums\StatusEnum;
use fanyou\error\FanYouHttpException;
use fanyou\tools\ArrayHelper;
use fanyou\models\base\SearchModel;

/**
	 * @author E-mail: Administrator@qq.com
	 * Create Time: 2019/04/24
	 */
class ShopEmployeeService 
{

/*********************ShopEmployee模块服务层************************************/
    /**
     * 添加店铺员工
     * @param ShopEmployeeModel $model
     * @return string
     * @throws \Throwable
     */
    public function addShopEmployee (ShopEmployeeModel $model): string
    {
        if($this->getAccountUnique($model->account)){
           throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,'该帐号已被管理方占用');
        }
        $model->insert();
        return $model->getPrimaryKey();
    }

    /**
     * 更新店铺员工信息
     * @param ShopEmployeeModel $model
     * @return int
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function updateShopEmployeeById(ShopEmployeeModel $model): int
    {
        $model->setOldAttribute('id',$model->id);
        return $model->update();
    }

    public function getAccountUnique($account)
    {
        return ShopEmployeeModel::findOne(['account'=>$account ]);
    }

    public function getUnique($account,$password)
    {
        return ShopEmployeeModel::findOne(['account'=>$account,'password'=>$password,'status'=>StatusEnum::ENABLED]);
    }
    /**
     * 获取员工列表
     * @param ShopEmployeeQuery $query
     * @return mixed
     * @throws \yii\web\NotFoundHttpException
     */
    public function getShopEmployeePage ( ShopEmployeeQuery $query){
        $searchModel = new SearchModel([
            'model' => ShopEmployeeModel::class,
            'scenario' => 'default',
            'partialMatchAttributes' => ['name','department_ids'], // 模糊查询
            'defaultOrder' => [
                'sort' => SORT_ASC,
                'is_admin' => SORT_DESC
            ],
            'pageSize'=>$query->limit,
        ]);
        $searchWord = $searchModel->search(array_filter($query->toArray()) ) ;
        $getResult=$searchWord->getModels();
        $result['list']= ArrayHelper::toArray($getResult);
        $result['totalCount']=$searchWord->pagination->totalCount;
        return $result;
    }
    /**
     * 根据Id删除部门
     * @param $id
     * @return int
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function deleteShopEmployee($id): int
    {
        $model = ShopEmployeeModel::findOne($id);
        return  $model->delete();
    }
}
/**********************End Of ShopEmployee 服务层************************************/ 


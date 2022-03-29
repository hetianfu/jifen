<?php

namespace api\modules\manage\service;

use api\modules\manage\models\forms\ShopEmployeeModel;
use fanyou\enums\HttpErrorEnum;
use fanyou\enums\StatusEnum;
use fanyou\error\FanYouHttpException;

/**
	 * @author E-mail: Administrator@qq.com
	 * Create Time: 2019/04/24
	 */
class ShopEmployeeService 
{

/*********************ShopEmployee模块服务层************************************/
    /**
     * @param $openId
     * @param string $account
     * @return array
     * @throws FanYouHttpException
     */
    public function login($openId)
    {
        $model=ShopEmployeeModel::findOne(['open_id'=>$openId,'status'=>StatusEnum::ENABLED]);
        if(empty($model) || empty($model->account)){
            return null;
        }
        return $model ;
    }

    public function loginByUserId($userId)
    {
        $model=ShopEmployeeModel::findOne(['user_id'=>$userId,'status'=>StatusEnum::ENABLED]);
        if(empty($model) || empty($model->account)){
            return null;
        }
        return $model ;
    }
    public function register($openId,$account,$password):array
    {
        $model=ShopEmployeeModel::findOne(['account'=>$account,'password'=>$password]);
        if( empty($model)){
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,'密码或帐号错误！');
        }
        if( empty($model->open_id)){
            if(empty($openId)){
                throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,'员工未绑定登陆！');
            }else{
                ShopEmployeeModel::updateAll(['open_id'=>$openId],['id'=>$model->id]);
            }
        }else if( !empty($account)){
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,'员工已绑定商户！');
        }
        return  $model->toArray();
    }
    /**
     * @param $mobile
     * @return ShopEmployeeModel|null
     */
    public function getOneByTelephone($mobile): ?ShopEmployeeModel
    {
        return  ShopEmployeeModel::findOne(['account'=>$mobile,'status'=>StatusEnum::ENABLED]);
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


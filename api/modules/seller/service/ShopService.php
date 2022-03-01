<?php

namespace api\modules\seller\service;

use api\modules\seller\models\forms\ShopInfoModel;
use api\modules\seller\models\forms\ShopInfoQuery;
use api\modules\seller\models\ShopInfoResult;
use fanyou\enums\QueryEnum;
use fanyou\models\base\SearchModel;
use fanyou\tools\ArrayHelper;
use yii\web\HttpException;

/**
 * Class ShopService
 * @package api\modules\seller\service
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-04-20 15:54
 */
class ShopService
{


    /**
     * 创建token
     * @param $id
     * @param  $merchantId
     * @param $roleIds
     * @return string
     * @throws HttpException
     * @throws \yii\base\Exception
     */
//    public function createAuthKey($id,$merchantId, $roleIds): string
//    {
//        $storeInfo = $this->getShopInfoById($id);
//        if (is_null($storeInfo) || is_null($storeInfo['status']) || $storeInfo['status'] < 0) {
//            throw new HttpException('401',   "店铺已过期或密码错误!");
//        }
//        $storeModel = new CacheShop();
//        $storeModel->setAttributes($storeInfo, false);
//        $array=$storeModel->toArray();
//        $array['merchantId'] = $merchantId;
//
//        $array['roleIds'] = $roleIds;
//        $token = Yii::$app->getSecurity()->generateRandomString();
//        // 加入IP 地址，及时间戳
//        $clientIp =Yii::$app->request->getRemoteIP();
//        if(!isset($array['settleTime'])){
//            $array['settleTime'] = 6;
//        }
//
//        $array['clientIp'] = $clientIp;
//        $array['timestamp'] = time();
//        Yii::$app->cache->set($token, json_encode($array), 3200);
//        return $token;
//    }
    /**
     * 获取session
     * @param $token
     * @return array
     */
//    public function getAuthInfo($token)
//    {
//        return  Yii::$app->cache->get($token);
//    }

/*********************ShopBasic模块服务层************************************/
    /**
     * 店铺登陆
     * @param $account
     * @param $password
     * @return ShopInfoModel|null
     */
//    public function   sellerLogIn($account,$password)
//    {
//        return  ShopInfoModel::findOne(['account_name'=>$account,'password'=>$password])  ;
//    }

    /**
     * 添加门店
     * @param ShopInfoModel $model
     * @return mixed
     * @throws \Throwable
     */
    public function   addShop(ShopInfoModel $model)
    {
        $model->insert();
        return $model->getPrimaryKey();
    }


    /**
     * 根据Id获取店铺基础信息
     * @param $id
     * @return array
     */
    public function getShopInfoById($id)
    {
        $shopInfo=ShopInfoModel::findOne(['id'=>$id]);
        $storeModel = new ShopInfoResult();
        $storeModel->setAttributes($shopInfo->toArray(), false);
        return  $storeModel;
    }

    /**
     * 根据Id更新
     * @param ShopInfoModel $model
     * @return int
     */
    public function updateById (ShopInfoModel $model): int
    {

        return ShopInfoModel::updateAll( $model->toArray() ,"id='{$model->id}'");
    }

    /**
     * 分页获取列表
     * @param ShopInfoQuery $query
     * @return mixed
     * @throws \yii\web\NotFoundHttpException
     */

    public function getPageList(ShopInfoQuery $query )
    {
        $searchModel = new SearchModel([
            'model' => ShopInfoModel::class,
            'scenario' => 'default',
            'defaultOrder' => [
                'created_at' => SORT_DESC
            ],
            'pageSize' => $query->limit,
        ]);
        $searchWord = $searchModel->search( $query->toArray([],[QueryEnum::LIMIT]) );
        $result['list'] = ArrayHelper::toArray($searchWord->getModels());
        $result['totalCount'] = $searchWord->pagination->totalCount;
        return $result;
    }

    /**
     * 删除门店
     * @param $id
     * @return int
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function delShopById( $id): int
    {   $model=ShopInfoModel::findOne($id);
        return $model->delete();
    }

//    public function getShopPostById($id)
//    {
//        $ShopInfo = $this->apiService->getShopPostById($id);
//        $result = new ShopPostResult();
//        $result->setAttributes($ShopInfo , false);
//        return  $result->toArray();
//    }
}
/**********************End Of ShopBasic 服务层************************************/ 


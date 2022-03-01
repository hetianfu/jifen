<?php

namespace api\modules\mobile\service;

use api\modules\mobile\models\forms\ShopInfoModel;
use api\modules\mobile\models\forms\ShopInfoQuery;
use fanyou\enums\QueryEnum;
use fanyou\tools\ArrayHelper;
use fanyou\models\base\SearchModel;

/**
	 * @author E-mail: Administrator@qq.com
	 * Create Time: 2019/03/06
	 */
class ShopService
{

/*********************ShopBasic模块服务层************************************/

    /**
     * 获取门店列表
     * @param ShopInfoQuery $query
     * @return mixed
     * @throws \yii\web\NotFoundHttpException
     */

    public function getList(ShopInfoQuery $query )
    {
        $searchModel = new SearchModel([
            'model' => ShopInfoModel::class,
            'scenario' => 'default',
            'defaultOrder' => [
                'created_at' => SORT_DESC
            ],
        ]);
        $searchWord = $searchModel->search( $query->toArray() );
        return ArrayHelper::toArray($searchWord->getModels());
    }
    public function getPage(ShopInfoQuery $query )
    {
        $searchModel = new SearchModel([
            'model' => ShopInfoModel::class,
            'scenario' => 'default',
            'defaultOrder' => [
                'created_at' => SORT_DESC
            ],
        ]);
        $searchWord = $searchModel->search( $query->toArray([],[QueryEnum::LIMIT]) );
        $result['list'] = ArrayHelper::toArray($searchWord->getModels());
        $result['totalCount'] = $searchWord->pagination->totalCount;
        return $result;

    }
    public function getById($id)
    {
        return  ShopInfoModel::findOne($id);

    }


}
/**********************End Of ShopBasic 服务层************************************/ 


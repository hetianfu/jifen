<?php

namespace api\modules\seller\service;

use api\modules\seller\models\forms\PayConfigModel;
use api\modules\seller\models\forms\PayConfigQuery;
use api\modules\seller\models\forms\ShopInfoModel;
use api\modules\seller\models\forms\ShopInfoQuery;
use api\modules\seller\models\ShopInfoResult;
use fanyou\enums\QueryEnum;
use fanyou\models\base\SearchModel;
use fanyou\tools\ArrayHelper;
use yii\web\HttpException;

/**
 * Class PayConfigService
 * @package api\modules\seller\service
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-04-20 15:54
 */
class PayConfigService
{


  /*********************ShopBasic模块服务层************************************/


  /**
   * 添加门店
   * @param PayConfigModel $model
   * @return mixed
   * @throws \Throwable
   */
  public function   add(PayConfigModel $model)
  {
    $model->insert();
    return $model->getPrimaryKey();
  }


  /**
   * 根据Id获取店铺基础信息
   * @param $id
   * @return array
   */
  public function getPayInfoById($id)
  {
    $payInfo = PayConfigModel::findOne(['id'=>$id]);
    $payModel = new PayConfigModel();
    $payModel->setAttributes($payInfo->toArray(), false);
    return  $payModel;
  }

  /**
   * 根据Id更新
   * @param ShopInfoModel $model
   * @return int
   */
  public function updateById (PayConfigModel $model): int
  {

    return PayConfigModel::updateAll( $model->toArray() ,"id='{$model->id}'");
  }

  /**
   * 分页获取列表
   * @param PayConfigQuery $query
   * @return mixed
   * @throws \yii\web\NotFoundHttpException
   */

  public function getPageList(PayConfigQuery $query): array
  {
    $searchModel = new SearchModel([
      'model' => PayConfigModel::class,
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
   * 删除
   * @param $id
   * @return int
   * @throws \Throwable
   * @throws \yii\db\StaleObjectException
   */
  public function delPayById( $id): int
  {
    $model=PayConfigModel::findOne($id);
    return $model->delete();
  }

}
/**********************End Of ShopBasic 服务层************************************/ 


<?php

namespace api\modules\seller\service;

use api\modules\seller\models\forms\ChannelModel;
use api\modules\seller\models\forms\ChannelQuery;
use fanyou\enums\QueryEnum;
use fanyou\enums\StatusEnum;
use fanyou\enums\SystemConfigEnum;
use fanyou\tools\ArrayHelper;
use fanyou\models\base\SearchModel;
use fanyou\tools\StringHelper;
use Yii;

/**
 * Class ChannelService
 * @package api\modules\seller\service
 * @author: HenryJia
 * @E-mail: 316560925@qq.com
 * @date: 2020-11-16
 */
class ChannelService
{

  /*********************Channel模块服务层************************************/
  /**
   * 添加一条渠道
   * @param ChannelModel $model
   * @return mixed
   * @throws \Throwable
   */
  public function addChannel(ChannelModel $model)
  {
    $model->insert();
    return $model->getPrimaryKey();
  }

  /**
   * 分页获取列表
   * @param ChannelQuery $query
   * @return array
   * @throws \yii\web\NotFoundHttpException
   */
  public function getChannelPage(ChannelQuery $query): array
  {
    $searchModel = new SearchModel([
      'model' => ChannelModel::class,
      'scenario' => 'default',
      'partialMatchAttributes' => ['name'], // 模糊查询
      'defaultOrder' => $query->getOrdersArray(),
      'pageSize' => $query->limit,
    ]);
    $searchWord = $searchModel->search($query->toArray([], [QueryEnum::LIMIT]));
    $result['list'] = ArrayHelper::toArray($searchWord->getModels());
    $result['totalCount'] = $searchWord->pagination->totalCount;
    return $result;
  }

  public function getList(ChannelQuery $query): array
  {
    $searchModel = new SearchModel([
      'model' => ChannelModel::class,
      'scenario' => 'default',
      'partialMatchAttributes' => ['name'], // 模糊查询
      'defaultOrder' => $query->getOrdersArray(),
      'pageSize' => $query->limit,
    ]);
    $searchWord = $searchModel->search($query->toArray());
    $list = ArrayHelper::toArray($searchWord->getModels());

    return $list;
  }

  /**
   * 根据Id获取渠道
   * @param $id
   * @return Object
   */
  public function getOneById($id)
  {
    $array = ChannelModel::find()->where(['id' => $id])->asArray()->one();
    return $array;
  }

  public function getHtmlOneById($id, $con = true)
  {
    if ($con) {
      $tokenId = SystemConfigEnum::REDIS_CHANNEL . $id ;
      $tokenContent = Yii::$app->cache->get($tokenId);
      if (!empty($tokenContent)) {
        return json_decode($tokenContent);
      }
    }


    if ($id) {
      $array = ChannelModel::find()->select(['id', 'short_url', 'score', 'short_url', 'name', 'brand'])->where(['id' => $id])->asArray()->one();;
    } else {

      $array = ChannelModel::find()->select(['id', 'short_url', 'score', 'short_url', 'name', 'brand'])->asArray()->one();
      $tokenId = SystemConfigEnum::REDIS_CHANNEL . $array['id'] ;
      $array['id'] = 0;
    }
    Yii::$app->cache->set($tokenId, json_encode(StringHelper::toCamelize($array)), 36000);
    return $array;
  }


  /**
   * 根据Id更新渠道
   * @param ChannelModel $model
   * @return int
   * @throws \Throwable
   * @throws \yii\db\StaleObjectException
   */
  public function updateChannelById(ChannelModel $model): int
  {
    $tokenId = SystemConfigEnum::REDIS_CHANNEL . $model->id ;
    Yii::$app->cache->delete($tokenId);
    $model->setOldAttribute('id', $model->id);
    return $model->update();
  }

  /**
   * 根据Id删除渠道
   * @param $id
   * @return int
   * @throws \Throwable
   * @throws \yii\db\StaleObjectException
   */
  public function deleteById($id): int
  {
    $model = ChannelModel::findOne($id);
    return $model->delete();
  }
}
/**********************End Of Channel 服务层************************************/


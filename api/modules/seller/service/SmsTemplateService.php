<?php

namespace api\modules\seller\service;

use api\modules\seller\models\forms\SmsTemplateModel;
use api\modules\seller\models\forms\SmsTemplateQuery;
use fanyou\enums\QueryEnum;
use fanyou\tools\ArrayHelper;
use fanyou\models\base\SearchModel;

/**
 * Class SmsTemplateService
 * @package api\modules\seller\service
 * @author: HenryJia
 * @E-mail: 316560925@qq.com
 * @date: 2020-11-16
 */
class SmsTemplateService
{

/*********************SmsTemplate模块服务层************************************/
	/**
	 * 添加一条短信模版
	 * @param SmsTemplateModel $model
     * @return mixed
     * @throws \Throwable
     */
	public function addSmsTemplate(SmsTemplateModel $model)
	{
		$model->insert();
		return $model->getPrimaryKey();
	}
	/**
	 * 分页获取列表
	 * @param SmsTemplateQuery $query
	 * @return array
	 * @throws \yii\web\NotFoundHttpException
	 */
	public function getSmsTemplatePage(SmsTemplateQuery $query): array
	{
		$searchModel = new SearchModel([
			'model' => SmsTemplateModel::class,
			'scenario' => 'default',
			'partialMatchAttributes' => ['name'], // 模糊查询
			'defaultOrder' => $query->getOrdersArray(),
			'pageSize' => $query->limit,
		]);
		$searchWord = $searchModel->search( $query->toArray([],[QueryEnum::LIMIT]) );
		$result['list'] = ArrayHelper::toArray($searchWord->getModels());
		$result['totalCount'] = $searchWord->pagination->totalCount;
		return $result;
	}

	/**
	 * 根据Id获取短信模版
	 * @param $id
	 * @return Object
	 */
	public function getOneById($id)
	{
		return SmsTemplateModel::findOne($id);
	}

	/**
	 * 根据Id更新短信模版
	 * @param SmsTemplateModel $model
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function updateSmsTemplateById (SmsTemplateModel $model): int
	{
		$model->setOldAttribute('id',$model->id);
		return $model->update();
	}
	/**
	 * 根据Id删除短信模版
	 * @param $id
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function deleteById ($id) : int
	{
		$model = SmsTemplateModel::findOne($id);
		return  $model->delete();
	}
}
/**********************End Of SmsTemplate 服务层************************************/


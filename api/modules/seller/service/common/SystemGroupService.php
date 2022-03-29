<?php

namespace api\modules\seller\service\common;

use api\modules\seller\models\forms\SystemGroupDataModel;
use api\modules\seller\models\forms\SystemGroupDataQuery;
use api\modules\seller\models\forms\SystemGroupModel;
use api\modules\seller\models\forms\SystemGroupQuery;
use api\modules\seller\service\BasicService;
use fanyou\tools\ArrayHelper;
use fanyou\models\base\SearchModel;
use fanyou\enums\QueryEnum;
use fanyou\enums\SortEnum;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-04-28
 */
class SystemGroupService extends BasicService
{

/*********************SystemGroup模块服务层************************************/
	/**
	 * 添加一条组合数据表
	 * @param SystemGroupModel $model
     * @return mixed
     * @throws \Throwable
     */
	public function addSystemGroup(SystemGroupModel $model)
	{
		$model->insert();
		return $model->getPrimaryKey();
	}
	/**
	 * 分页获取列表
	 * @param SystemGroupQuery $query
	 * @return array
	 * @throws \yii\web\NotFoundHttpException
	 */
	public function getSystemGroupPage(SystemGroupQuery $query): array
	{
		$searchModel = new SearchModel([
			'model' => SystemGroupModel::class,
			'scenario' => 'default',
			'partialMatchAttributes' => ['name'], // 模糊查询
			'defaultOrder' => $query->getOrdersArray(SortEnum::SHOW_ORDER,SORT_ASC),
			'pageSize' => $query->limit,
		]);
		$searchWord = $searchModel->search( $query->toArray([],[QueryEnum::LIMIT]) );
		$result['list'] = ArrayHelper::toArray($searchWord->getModels());
		$result['totalCount'] = $searchWord->pagination->totalCount;
		return $result;
	}
    public function getSystemGroupList(SystemGroupQuery $query)
    {
        $searchModel = new SearchModel([
            'model' => SystemGroupModel::class,
            'scenario' => 'default',
            'partialMatchAttributes' => ['name'], // 模糊查询
            'defaultOrder' => $query->getOrdersArray(SortEnum::SHOW_ORDER,SORT_ASC),
        ]);
        $searchWord = $searchModel->search( $query->toArray([],[QueryEnum::LIMIT]) );
        return $searchWord->getModels();
    }
	/**
	 * 根据Id获取据表
	 * @param $id
	 * @return Object
	 */
	public function getOneById($id):?SystemGroupModel
	{
		return SystemGroupModel::findOne($id);
	}

	/**
	 * 根据Id更新据表
	 * @param SystemGroupModel $model
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function updateSystemGroupById (SystemGroupModel $model): int
	{
		$model->setOldAttribute('id',$model->id);
		return $model->update();
	}
	/**
	 * 根据Id删除据表
	 * @param $id
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function deleteById ($id) : int
	{
		$model = SystemGroupModel::findOne($id);
		return  $model->delete();
	}

    /*********************SystemGroupData模块服务层************************************/
    /**
     * 添加一条组合数据详情表
     * @param SystemGroupDataModel $model
     * @return mixed
     * @throws \Throwable
     */
    public function addSystemGroupData(SystemGroupDataModel $model)
    {
        $model->insert();
        return $model->getPrimaryKey();
    }
    /**
     * 分页获取列表
     * @param SystemGroupDataQuery $query
     * @return array
     * @throws \yii\web\NotFoundHttpException
     */
    public function getSystemGroupDataPage(SystemGroupDataQuery $query): array
    {
        $searchModel = new SearchModel([
            'model' => SystemGroupDataModel::class,
            'scenario' => 'default',
            'partialMatchAttributes' => ['name'], // 模糊查询
            'defaultOrder' =>$query->getOrdersArray(SortEnum::SHOW_ORDER,SORT_ASC),
            'pageSize' => $query->limit,
        ]);
        $searchWord = $searchModel->search( $query->toArray([],['limit']));
        $result['list'] = ArrayHelper::toArray($searchWord->getModels());
        $result['totalCount'] = $searchWord->pagination->totalCount;
        return $result;
    }
    public function getSystemGroupDataList(SystemGroupDataQuery $query): array
    {
        $searchModel = new SearchModel([
            'model' => SystemGroupDataModel::class,
            'scenario' => 'default',
            'partialMatchAttributes' => ['name'], // 模糊查询
            'defaultOrder' =>$query->getOrdersArray(SortEnum::SHOW_ORDER,SORT_ASC),
        ]);
        $searchWord = $searchModel->search( $query->toArray());

        return ArrayHelper::toArray($searchWord->getModels());
    }
    /**
     * 根据Id获取据详情表
     * @param $id
     * @return Object
     */
    public function getSystemGroupDataById($id)
    {
        return SystemGroupDataModel::findOne($id);
    }

    /**
     * 根据Id更新据详情表
     * @param SystemGroupDataModel $model
     * @return int
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function updateSystemGroupDataById (SystemGroupDataModel $model): int
    {
        $model->setOldAttribute('id',$model->id);

        return $model->update();
    }
    /**
     * 根据Id删除据详情表
     * @param $id
     * @return int
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function deleteSystemGroupDataById ($id) : int
    {
        $model = SystemGroupDataModel::findOne($id);
        return  $model->delete();
    }

}
/**********************End Of SystemGroup 服务层************************************/


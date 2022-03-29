<?php

namespace api\modules\seller\service\common;

use api\modules\seller\models\forms\SystemConfigQuery;
use api\modules\seller\models\forms\SystemConfigTabModel;
use api\modules\seller\models\forms\SystemConfigTabQuery;
use api\modules\seller\models\forms\SystemConfigTabValueModel;
use api\modules\seller\service\BasicService;
use fanyou\enums\QueryEnum;
use fanyou\tools\ArrayHelper;
use fanyou\models\base\SearchModel;
use fanyou\enums\SortEnum;
use fanyou\enums\StatusEnum;
use fanyou\enums\SystemConfigEnum;
use yii\db\StaleObjectException;
use yii\web\NotFoundHttpException;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-04-28
 */
class SystemConfigService extends BasicService
{


    /*********************SystemConfigTab模块服务层************************************/

    /**
     * 添加一条配置分类表
     * @param SystemConfigTabModel $model
     * @return mixed
     * @throws \Throwable
     */
    public function addTab(SystemConfigTabModel $model)
    {
        $model->insert();
        return $model->getPrimaryKey();
    }

    public function verifyEngTitleRepeat($id, $newEngTitle):int
    {
        $result=0;
        $oldModel= $this->getOneTabById($id);
        if(!($oldModel['eng_title']=== $newEngTitle )){
            if($this->countTab($newEngTitle)){
                $result=1;
            }
        }
        return $result;
    }
    public function countTab( $engTitle):int
    {
        return SystemConfigTabModel::find()->select(['count(1)'])->where(['eng_title'=>$engTitle])->count();
    }

    /**
     * 分页获取列表
     * @param SystemConfigTabQuery $query
     * @return array
     * @throws NotFoundHttpException
     */
    public function getTabPage(SystemConfigTabQuery $query): array
    {

        $searchModel = new SearchModel([
            'model' => SystemConfigTabModel::class,
            'scenario' => 'default',
            'partialMatchAttributes' => ['title'], // 模糊查询
            'defaultOrder' => $query->getOrdersArray(),
            'pageSize' => $query->limit,
        ]);
        $searchWord = $searchModel->search( $query->toArray() );

        $result['list'] = ArrayHelper::toArray($searchWord->getModels());
        $result['totalCount'] = $searchWord->pagination->totalCount;
        return $result;
    }

    /**
     * 根据Id获取类表
     * @param $id
     * @return SystemConfigTabModel
     */
    public function getOneTabById($id):?SystemConfigTabModel
    {
        return SystemConfigTabModel::findOne($id);
    }
    /**
     * 根据engTitle获取
     * @param $engTitle
     * @return Object
     */
    public function getOneTabByEngTitle($engTitle)
    {
        return SystemConfigTabModel::findOne(['eng_title'=>$engTitle]);
    }
    /**
     * 根据Id更新类表
     * @param SystemConfigTabModel $model
     * @return int
     * @throws \Throwable
     * @throws  StaleObjectException
     */
    public function updateTabById (SystemConfigTabModel $model): int
    {
        $oldModel=SystemConfigTabModel::findOne($model->id);
        $this->cleanCache($oldModel->eng_title,$oldModel->type);

        $model->setOldAttribute('id',$model->id);
        return $model->update();
    }
    /**
     * 根据Id删除类表
     * @param $id
     * @return int
     * @throws \Throwable
     * @throws StaleObjectException
     */
    public function deleteTabById ($id) : int
    {
        $model = SystemConfigTabModel::findOne($id);
        $this->cleanCache($model->eng_title,$model->type);

        $result=$model->delete();
        if($result){
            SystemConfigTabValueModel::deleteAll(['config_tab_id'=>$id]);
        }
        return  $result;
    }


/*********************SystemConfig模块服务层************************************/

/**
	 * 添加一条配置表
	 * @param SystemConfigTabValueModel $model
     * @return mixed
     * @throws \Throwable
     */
	public function addSystemConfig(SystemConfigTabValueModel $model)
	{
		$model->insert();
		return $model->getPrimaryKey();
	}
	/**
	 * 分页获取列表
	 * @param SystemConfigQuery $query
	 * @return array
	 * @throws  NotFoundHttpException
	 */
	public function getSystemConfigPage(SystemConfigQuery $query): array
	{
		$searchModel = new SearchModel([
			'model' => SystemConfigTabValueModel::class,
			'scenario' => 'default',
			'partialMatchAttributes' => ['menu_name'], // 模糊查询
            'defaultOrder' =>$query->getOrdersArray(SortEnum::SHOW_ORDER,SORT_ASC),
			'pageSize' => $query->limit,
		]);
		$searchWord = $searchModel->search($query->toArray([],[QueryEnum::LIMIT]));

		$result['list'] =  ArrayHelper::toArray($searchWord->getModels());
		$result['totalCount'] = $searchWord->pagination->totalCount;
		return $result;
	}
    public function getSystemConfigList(SystemConfigQuery $query): array
    {
        $searchModel = new SearchModel([
            'model' => SystemConfigTabValueModel::class,
            'scenario' => 'default',
            'partialMatchAttributes' => ['menu_name'], // 模糊查询
            'defaultOrder' =>$query->getOrdersArray(SortEnum::SHOW_ORDER),
        ]);
        $searchWord = $searchModel->search($query->toArray());
        return ArrayHelper::toArray($searchWord->getModels());
    }
	/**
	 * 根据Id获取
	 * @param $id
	 * @return SystemConfigTabValueModel
	 */
	public function getSystemConfigById($id):?SystemConfigTabValueModel
	{
		return SystemConfigTabValueModel::findOne($id);
	}

	/**
	 * 根据Id更新
	 * @param SystemConfigTabValueModel $model
	 * @return int
	 * @throws \Throwable
	 * @throws  StaleObjectException
	 */
	public function updateSystemConfigById (SystemConfigTabValueModel $model): int
	{
		$model->setOldAttribute('id',$model->id);

        $result=$model->update();
        if($result){
            $this->cleanConfigTabRedis($model->id);
        }
		return $result;
	}

    /**
     * 批量修改系统配置值
     * @param array $array
     * @return int
     */
    public function batchUpdateSystemConfigById (array $array)
    {

        foreach ($array as $k=>$v){

            SystemConfigTabValueModel::updateAll(['value'=>$v['value']],['id'=>$v['id']]) ;
        }

        return count($array);
    }

	/**
	 * 根据Id删除
	 * @param $id
	 * @return int
	 * @throws \Throwable
	 * @throws  StaleObjectException
	 */
	public function deleteById ($id) : int
	{
		$model = SystemConfigTabValueModel::findOne($id);
		return  $model->delete();
	}

    /**
     * 查询配置信息
     * @param int $id 指定获取的配置信息
     * @return array|\yii\db\ActiveRecord[]
     */
    public function findTabWithValue($id)
    {
        $configTab= SystemConfigTabModel::findOne($id)->toArray();
        if($configTab){
        $configTabValue=SystemConfigTabValueModel::find()->where(['config_tab_id' => $id])->asArray()->all();
        $configTab['value']=$configTabValue;
        }
        return $configTab;
    }

    /**
     * 根据唯一索引查询配置
     * @param $merchantId
     * @param $engTitle
     * @param $type
     * @return array|\yii\db\ActiveRecord|null
     */
    public function findTabWithValueUnique($merchantId,$engTitle,$type)
    {
        $configTab= SystemConfigTabModel::find()->where(['merchant_id'=>$merchantId,'eng_title'=>$engTitle,'type'=>$type])->asArray()->one();
        if($configTab){
            $configTabValue=SystemConfigTabValueModel::find()->where(['config_tab_id' => $configTab['id']])->asArray()->all();
            $configTab['value']=$configTabValue;
        }
        return $configTab;
    }

    /**
     * 提取配置值
     * @param $engTitle
     * @param $type
     * @param $showAll
     * @return array|false|void
     */
    public function findConfigValueUnique($engTitle,$showAll=false)
    {
        $configTab= SystemConfigTabModel::find()->where(['eng_title'=>$engTitle])->asArray()->one();
        if($configTab){
            if(!$showAll){

            return SystemConfigTabValueModel::find()
                    ->select(['id','menu_name','info','type', 'value','status','show_order','parameter','upload_type','desc'])
                    ->where(['config_tab_id' => $configTab['id'],'status'=>StatusEnum::ENABLED])
                    ->orderBy(['show_order'=>SORT_ASC])
                    ->asArray()
                    ->all();
            }else{
                return  SystemConfigTabValueModel::find()
                    ->select(['id','menu_name','info','type', 'value','status','show_order','parameter','upload_type','desc'])
                    ->where(['config_tab_id' => $configTab['id']])
                    ->orderBy(['show_order'=>SORT_ASC])
                    ->asArray()
                    ->all();
            }
        }
        return  ;
    }

    /**
     * 获取有效的配置值
     * @param $configTabId
     * @return array|\yii\db\ActiveRecord[]
     */
    public function findEffectConfigValue($configTabId)
    {
        return SystemConfigTabValueModel::find()->select(['menu_name','value'])->where(['config_tab_id' => $configTabId,'status' => StatusEnum::ENABLED])->asArray()->all();

    }



    /**
     * 获取微信配置
     * @param $engTitle
     * @param $type
     * @return bool
     */
    public function  getSystemConfigValue($engTitle=SystemConfigEnum::WX_MP){

        return $this->findConfigValueUnique($engTitle);

    }

    /**
     * 根据配置值清除缓存
     * @param $valueId
     */
    private function  cleanConfigTabRedis($valueId){
        $model=$this->getSystemConfigById($valueId);
        $tab=$this->getOneTabById($model->config_tab_id);
        $this->cleanCache( $tab->eng_title,$tab->type);

}
    /**
     * 根据系统TabI清除缓存
     * @param $id
     * @return int
     */
    public function  cleanCacheByTabId($id){
        $model = SystemConfigTabModel::findOne($id);
        if(empty($model)){
            return StatusEnum::DISABLED;
        }
        $this->cleanCache($model->eng_title,$model->type);
        return StatusEnum::ENABLED;
    }

    /**
     * 根据eng_title清除缓存
     * @param $engTitle
     * @param int $type
     * @return bool
     */
    public function  cleanCache($engTitle,$type=StatusEnum::APP){

        $cacheKey = SystemConfigEnum::getPrefix($engTitle,$type);

        return parent::deleteCache($cacheKey);
    }
}
/**********************End Of SystemConfig 服务层************************************/


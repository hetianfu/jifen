<?php

namespace api\modules\mobile\service ;

use api\modules\mobile\models\forms\SystemGroupModel;
use api\modules\mobile\models\forms\SystemGroupQuery;
use api\modules\mobile\models\IndexPageProductResult;
use api\modules\seller\models\forms\SystemGroupDataModel;
use fanyou\enums\entity\SystemGroupEnum;
use fanyou\enums\SortEnum;
use fanyou\models\base\SearchModel;
use fanyou\tools\ArrayHelper;
use fanyou\tools\GroupHelper;

/**
 * Class SystemGroupService
 * @package api\modules\mobile\service
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020/6/1 0001 : 9:58
 */
class SystemGroupService extends BasicService
{

/*********************SystemGroup模块服务层************************************/

	/**
	 * 分页获取列表
	 * @param SystemGroupQuery $query
	 * @return array
	 * @throws \yii\web\NotFoundHttpException
	 */
	public function getSystemGroupList(SystemGroupQuery $query): array
	{
		$searchModel = new SearchModel([
			'model' => SystemGroupModel::class,
			'scenario' => 'default',
			'partialMatchAttributes' => ['name'], // 模糊查询
			'defaultOrder' => [
			'created_at' => SORT_DESC
			],
		]);
		$searchWord = $searchModel->search( $query->toArray() );
		return ArrayHelper::toArray($searchWord->getModels());
	}

    public function getSystemGroupWithValues($id): array
    {
        $systemGroup= SystemGroupModel::findOne($id);
        $result=[];
        if(empty($systemGroup)){
            return $result;
        }

        switch ($systemGroup->type){

            case SystemGroupEnum::NORMAL_TYPE:

                $values=SystemGroupDataModel::find()
                    ->select(['value','show_order' ])
                    ->where(['gid'=>$systemGroup->id])
                    ->orderBy([SortEnum::SHOW_ORDER=>SORT_ASC])
                    ->asArray()->all();

                if(empty($values)){
                    return $systemGroup->toArray();
                }
                foreach ($values as $k=>$v){
                    $value=json_decode($v['value']);
                    $a=[];
                    foreach($value as $t=>$kd){
                        $a[$t]=ArrayHelper::toArray($kd)['value'];
                    }
                    $result[]=$a;
                }
                break;
            case SystemGroupEnum::STRATEGY_TYPE:
                $g=new GroupHelper(SystemGroupEnum::STRATEGY_TYPE,$systemGroup->id);
                $systemGroup->config_name='diy';
                $array=$g->getGroupValue() ;
                foreach ($array as $key=>$value){
                    $model=new IndexPageProductResult;
                    $model->setAttributes($value ,false);
                    $result[]= $model->toArray();
                }
                break;
            case SystemGroupEnum::PRODUCT_TYPE:

                $g=new GroupHelper(SystemGroupEnum::PRODUCT_TYPE,$systemGroup->id);
                $systemGroup->config_name='diy';
                $array=$g->getGroupValue() ;
                foreach ($array as $key=>$value){
                    $model=new IndexPageProductResult;
                    $model->setAttributes($value ,false);
                    $result[]= $model->toArray();
                }


                break;
        }

        $array=$systemGroup->toArray() ;
        $array['items']=$result;


        return $array;
    }
    /**
     * 取所有列表
     * @param $id
     * @return array
     * @throws \yii\web\NotFoundHttpExceptionf
     */
    public function getSystemGroupWithValuesOld($id): array
    {
        $systemGroup= SystemGroupModel::findOne($id);

        $values=SystemGroupDataModel::find()->select(['value','sort','config_name'])->where(['gid'=>$systemGroup->id])->asArray()->all();
        if(empty($values)){
            return $systemGroup->toArray();
        }
        $result=[];
        foreach ($values as $k=>$v){
            //$groupValues=[];
            $value=json_decode($v['value']);
          //  $sort= $v['sort'] ;
            $a=[];
            foreach($value as $t=>$kd){
                $a[$t]=ArrayHelper::toArray($kd)['value'];
            }
            $groupValues=$a ;
            $result[]=$groupValues;
        }
        $array=$systemGroup->toArray() ;
        $array['items']=$result;


        return $array;
    }
	/**
	 * 根据Id获取据表
	 * @param $id
	 * @return Object
	 */
	public function getOneById($id)
	{
		return SystemGroupModel::findOne($id);
	}


}
/**********************End Of SystemGroup 服务层************************************/


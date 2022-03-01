<?php

namespace api\modules\seller\service;

use api\modules\seller\models\forms\CategoryTagDetailModel;
use api\modules\seller\models\forms\CategoryTagModel;
use api\modules\seller\models\forms\ProductSpecModel;
use api\modules\seller\models\forms\ProductSpecQuery;
use api\modules\seller\models\forms\ProductSpecRequest;
use api\modules\seller\models\SpecResult;
use api\modules\seller\models\SpecsTag;
use fanyou\models\base\SearchModel;
use fanyou\tools\ArrayHelper;
use fanyou\tools\StringHelper;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-03-23
 */
class CategoryTagService extends BasicService
{

    /*********************CategoryTag模块服务层************************************/
    /**
     * 添加商品规格
     * @param string $id
     * @param $array
     * @return string|void
     * @throws \Throwable
     * @throws \yii\base\Exception
     */
    public function addProductSpec($id='',$array)
    {
        if(empty($id)){
            $id=StringHelper::uuid();
        }
        $now = time();

        $pSpecs = new  ProductSpecRequest();
        $pSpecs->setAttributes($array);

        $model = new ProductSpecModel();
        $model->name = $pSpecs->name;

        $model->merchant_id= $pSpecs->merchant_id;//  Yii::$app->user->identity['merchantId'];

        $tagArray = array_column( $pSpecs->spec_list , 'name');

        $model->spec_tag = implode(',',$tagArray  );

        $model->spec_tag_detail = implode(',',array_column($pSpecs->spec_list, 'value') );

        $model->id= $id;

        $specsTag = $this->fillCategoryTag($model->id, $pSpecs->spec_list,$now);
        if(empty($this->addCategoryTagDetailList($specsTag))){
            return;
        }
        if(empty($this->addCategoryTagList($specsTag))){
            return;
        }
        if(!$model->insert()){
            return;
        }
        return $model->id;
    }

    /**
     * 分页获取列表
     * @param ProductSpecQuery $query
     * @return array
     * @throws \yii\web\NotFoundHttpException
     */
    public function getProductSpecPage(ProductSpecQuery $query): array
    {
        $searchModel = new SearchModel([
            'model' => ProductSpecModel::class,
            'scenario' => 'default',
            'partialMatchAttributes' => ['name'], // 模糊查询
            'defaultOrder' => [
                'created_at' => SORT_DESC
            ],
            'pageSize' => $query->limit,
        ]);
        $searchWord = $searchModel->search( $query->toArray([],['limit']) );
        $result['list'] = ArrayHelper::toArray($searchWord->getModels());
        $result['totalCount'] = $searchWord->pagination->totalCount;
        return $result;
    }

    /**
     * 获取所有列表
     * @param ProductSpecQuery $query
     * @return array
     * @throws \yii\web\NotFoundHttpException
     */
    public function getProductSpecList(ProductSpecQuery $query): array
    {
        $searchModel = new SearchModel([
            'model' => ProductSpecModel::class,
            'scenario' => 'default',
            'partialMatchAttributes' => ['name'], // 模糊查询
            'defaultOrder' => [
                'created_at' => SORT_DESC
            ],
        ]);
        $searchWord = $searchModel->search( $query->toArray() );
        return  ArrayHelper::toArray($searchWord->getModels());;
    }
    /**
     * 根据Id获取商品规格
     * @param $id
     * @return array
     */
    public function getSpecById($id)
    {
        $result=new ProductSpecRequest();
        $spec = ProductSpecModel::findOne($id);
        $specTagList = CategoryTagModel::find()->select(['id', 'name', 'show_order'])->where(['spec_id' => $id])->asArray()->all();
        $specList=[];
        foreach ($specTagList as $k => $v) {
            $specResult=new SpecResult();
            $specTagDetailList = CategoryTagDetailModel::find()->select(['id', 'name', 'show_order','tag_id'])->where(['tag_id' => $v['id']])->asArray()->all();
            $specResult->name=$v['name'];
            $specResult->value=implode(",",array_column( $specTagDetailList,"name")) ;
            $specList[]=$specResult;
        }
        $result->id=$id;
        $result->name=$spec->name;
        $result->spec_list=$specList;
        return $result->toArray();
    }

    /**
     * 根据Id更新格
     * @param $array
     * @return int
     * @throws \Throwable
     * @throws \yii\base\Exception
     * @throws \yii\db\StaleObjectException
     */
    public function updateSpecById($array)
    {   $id=$array['id'];
       if($this->deleteSpecById($id)){
           return $this->addProductSpec($id,$array);
       }
    }
    /**
     * 根据Id删除格
     * @param $id
     * @return bool
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function deleteSpecById($id)
    {
        if(empty($id)){
            return true;
        }

        CategoryTagDetailModel::deleteAll(['spec_id'=>$id]);

        CategoryTagModel::deleteAll(['spec_id'=>$id]);

        $model = ProductSpecModel::findOne($id);

        if(empty($model)){
            return true;
        }
        return $model->delete();
    }


    /**
     * 组装商品规格内容
     * @param $specId
     * @param $array
     * @param $now
     * @return SpecsTag
     * @throws \yii\base\Exception
     */
    public function fillCategoryTag($specId, $array, $now)
    {

        $specsTag = new SpecsTag();
        $field = ['name','id','spec_id',  'show_order',
            'created_at', 'updated_at'];
        sort($field);
        $detailField = ['spec_id','tag_id', 'name', 'show_order',
            'created_at', 'updated_at'];
        sort($detailField);
        $rows = [];
        $detailRows = [];
        foreach ($array as $key => $val) {
            $tagId=parent::getRandomId();
            $val['id']=$tagId;
            $val['spec_id'] = $specId;
            if (empty($val['showOrder'])) {
                $val['show_order'] = 0;
            }
            $val['created_at'] = $now;
            $val['updated_at'] = $now;
            $values= explode(',',$val['value'] );
            $detailRows= array_merge($detailRows, $this->fillCategoryTagDetail($specId,$tagId,$values,$now));
            unset($val['showOrder'],$val['input_visible'],$val['value']);
            ksort($val);
            $rows[] = $val;
        }
        $specsTag->detailColumn=$detailField;
        $specsTag->detailRows=$detailRows;

        $specsTag->column = $field;
        $specsTag->rows = $rows;
        return $specsTag;
    }
    public function fillCategoryTagDetail($specId,$tagId, $values, $now)
    {  $detailRows = [];
        foreach ($values as $k => $v) {
            $detail['spec_id'] = $specId;
            $detail['tag_id'] = $tagId;
            $detail['name'] = $v;
            if (empty($v['show_order'])) {
                $detail['show_order'] = 0;
            }
            $detail['created_at'] = $now;
            $detail['updated_at'] = $now;
            ksort($detail);
            $detailRows[] = $detail;
        }
        return $detailRows;
    }

    // 批量写入数据
    public function addCategoryTagList(SpecsTag $tag)
    {
        $column = $tag->column;
        $rows = $tag->rows;
        !empty($rows) && parent::DbCommand()->batchInsert(CategoryTagModel::tableName(), $column, $rows)->execute();
        return count($rows);
    }
    public function addCategoryTagDetailList(SpecsTag $tag)
    {
        $column = $tag->detailColumn ;
        $rows = $tag->detailRows;
        !empty($rows) && parent::DbCommand()->batchInsert(CategoryTagDetailModel::tableName(), $column, $rows)->execute();
        return count($rows);
    }
    // 批量写入数据
    public function addSpecsList($id, $array)
    {
        $rows = [];
        $field = ['tag_id', 'name', 'show_order',
            'created_at', 'updated_at'];
        $now = time();
        foreach ($array as $key => $val) {
            $val['tag_id'] = $id;
            $val['created_at'] = $now;
            $val['updated_at'] = $now;
            if (empty($val['show_order'])) {
                $val['show_order'] = 0;
            }
            $rows[] = $val;
        }
        !empty($rows) && parent::DbCommand()->batchInsert(CategoryTagDetailModel::tableName(), $field, $rows)->execute();
        return count($rows);
    }





}
/**********************End Of CategoryTag 服务层************************************/ 


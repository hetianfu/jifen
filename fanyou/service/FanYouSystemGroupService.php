<?php

namespace fanyou\service;

use api\modules\seller\models\forms\SystemConfigTabModel;
use api\modules\seller\models\forms\SystemConfigTabValueModel;
use api\modules\seller\models\forms\SystemGroupDataModel;
use fanyou\common\FormatProductResult;
use fanyou\enums\entity\BasicConfigEnum;
use fanyou\enums\StatusEnum;
use fanyou\enums\SystemConfigEnum;
use fanyou\tools\ArrayHelper;
use fanyou\enums\entity\SystemGroupEnum;
use fanyou\enums\SortEnum;
use fanyou\tools\GroupHelper;
use fanyou\tools\StringHelper;
use Yii;

/**
 * Class FanYouSystemGroupService
 * @package fanyou\service
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-06-10 10:34
 */
class FanYouSystemGroupService
{
    public static function getSystemGroupDate( $systemGroup,$allShow=false,$page=1,$limit=20): array
    {    $result=[];
        if(empty($systemGroup)){
            return $result;
        }
        $array=$systemGroup->toArray();
        switch ($systemGroup->type){
            case SystemGroupEnum::NORMAL_TYPE:
                if($allShow){
                $values=SystemGroupDataModel::find()
                    ->select(['value',  'show_order' ])
                    ->where(['gid'=>$systemGroup->id])
                    ->orderBy([SortEnum::SHOW_ORDER=>SORT_ASC])
                    ->asArray()
                    ->all();
                }else{
                    $values=SystemGroupDataModel::find()
                        ->select(['value',  'show_order' ])
                        ->where(['gid'=>$systemGroup->id,'status'=>StatusEnum::ENABLED])
                        ->orderBy([SortEnum::SHOW_ORDER=>SORT_ASC])
                        ->asArray()
                        ->all();
                }

                if(empty($values)){
                    return $systemGroup->toArray();
                }

                foreach ($values as $k=>$v){
                    $value=json_decode($v['value']);
                    $a=[];
                    foreach($value as $t=>$kd){
                      $arr = ArrayHelper::toArray($kd);
                      $a[$t] = isset($arr['value']) ?  $arr['value'] : '';

                    }
                   // $a['show_order']=$v['show_order'];
                    $result[]=$a;
                }
                break;
            case SystemGroupEnum::STRATEGY_TYPE:
            case SystemGroupEnum::PRODUCT_TYPE:
            case SystemGroupEnum::PINK_TYPE:
                if(empty($systemGroup->fields)){
                    break;
                }
                $g=new GroupHelper($systemGroup->type,$systemGroup->id,StringHelper::arrayToString(json_decode($systemGroup->fields)),$page,$limit  );
                $systemGroup->config_name='diy';

                $list=$g->getGroupValue($allShow) ;
                $totalCount=$g->getGroupTotalCount();
                foreach ($list as $key=>$value){
                    $model=new FormatProductResult;
                    $model->setAttributes($value ,false);
                    $result[]=  $model->toArray();
                }
                $array['totalCount']=$totalCount;
                break;
        }
        $array['items']=$result;
        return $array;
    }

    public static function getDm(){
        $tokenId = "fy-gt-do" .SystemConfigEnum::BASIC_CONFIG;
        $tokenContent = Yii::$app->cache->get($tokenId);

        if (!empty($tokenContent)) {
            return $tokenContent;
        }
        $domain='';
        $configTab= SystemConfigTabModel::find()->select(['id'])->where(['eng_title'=>SystemConfigEnum::BASIC_CONFIG])->asArray()->one();
        if($configTab){
            $arr= SystemConfigTabValueModel::find()
                ->select([ 'menu_name','value'])
                ->where(['config_tab_id' => $configTab['id']])
                ->asArray()
                ->all();

            foreach ($arr as $k=>$v)   {
                if($v['menu_name']==BasicConfigEnum::BASIC_SITE){
                    $domain=$v['value'];
                }
            }
            Yii::$app->cache->set($tokenId, $domain, 864000);
            return $domain;
        }
    }

}

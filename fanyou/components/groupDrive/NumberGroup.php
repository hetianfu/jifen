<?php
namespace fanyou\components\groupDrive;

use api\modules\mobile\models\forms\SaleProductModel;
use api\modules\mobile\models\forms\SystemGroupModel;
use api\modules\mobile\service\SaleProductStrategyService;
use fanyou\enums\AppEnum;
use fanyou\enums\entity\StrategyTypeEnum;
use fanyou\enums\StatusEnum;
use fanyou\tools\ArrayHelper;

/**
 *  限购活动
 * @author leedong
 *        
 */
class NumberGroup extends GroupInterface
{
    protected $limitNumber=0;
    protected $systemGroup;
    protected $type=AppEnum::NUMBER;
    protected $strategyType=StrategyTypeEnum::NUMBER;
    protected $saleProductService;
    /**
     * 初始化
     */
    protected function create(){
       $this->limitNumber= SystemGroupModel::findOne(['config_name'=>$this->type],['type'=>StatusEnum::SYSTEM])->limit_number;
       $this->saleProductService=new SaleProductStrategyService();
    }
    /**
     * 获取系统的值
     */
    public function getValue()
    {
        if(empty($this->limitNumber)){
            $array= SaleProductModel::find()
                ->where(['strategy_type'=>$this->strategyType,'on_show'=>StatusEnum::ENABLED])
                ->orderBy(['show_order'=>SORT_DESC])
                ->all();
            $result=ArrayHelper::toArray($array);
            foreach ($result as $k=>$v){
                $result[$k]=array_merge($v,$this->saleProductService->getTodayEffectTimeById($v));
            }
         return  $result;
        }else{
            $array= SaleProductModel::find()->where(['strategy_type'=>$this->strategyType,'on_show'=>StatusEnum::ENABLED])
            ->offset(1)->limit($this->limitNumber)
            ->orderBy(['show_order'=>SORT_DESC])
            ->all();
            $array=ArrayHelper::toArray($array);
            foreach ($array as $k=>$v){
                $array[$k]=array_merge($v,$this->saleProductService->getTodayEffectTimeById($v));
            }
            return   $array;
        }
    }

    protected function getTotalCount()
    {
        // TODO: Implement getTotalCount() method.
    }
}


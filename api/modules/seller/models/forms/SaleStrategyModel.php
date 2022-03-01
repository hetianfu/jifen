<?php
namespace api\modules\seller\models\forms;
/**
 * This is the model class for table "{{%sale_strategy}}".
 *
 * @property  $id  ;
 * @property string $merchant_id merchantId 代理id;
 * @property string $strategy_type strategyType 活动类型---
秒杀--SECKILL
限量--NUMBER
限时--TIMER
促销--SALES;
 * @property string $name  秒杀名称;
 * @property int  $status  状态 ，0生效，1失效;
 * @property int  $on_show onShow 0下架，1上架;
 * @property string $city  城市;
 * @property int  $start_hour startHour 开始时间（小时）;
 * @property int  $remain_hour remainHour 活动持续时间;
 * @property int  $created_at createdAt ;
 * @property int  $updated_at updatedAt ;
 */
class SaleStrategyModel extends Base{
    public static function tableName()
    {
        return '{{%sale_strategy}}';
    }

    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['id'],'required','on' => [ 'update' ]],
            [['merchant_id','strategy_type','name','city',],'string','on' => [ 'default','update']],
            [['status','on_show','start_hour','remain_hour', ], 'integer','on' => [ 'default','update']],
        ], $rules);
    }


    public function fields(){
        $fields = parent::fields();
        return $fields;
    }

    public function setAttributes($values, $safeOnly = true) {
        parent::setAttributes($values, $safeOnly);
    }
}


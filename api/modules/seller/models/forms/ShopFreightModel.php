<?php
namespace api\modules\seller\models\forms;
/**
 * This is the model class for table "{{%shop_freight}}".
 *
 * @property string $id  ;
 * @property int  $method  0-默认方式：固定邮费，满多少包邮，1，按距离算邮费;
 * @property int  $post_amount postAmount method-为0的时候生效;
 * @property int  $add_post_amount addPostAmount 加配送费金额;
 * @property string $add_post_period addPostPeriod 策略时间段;
 * @property  $post_scope postScope 配送范围，方圆多少公里;
 * @property int  $distance_price distancePrice 每km需要加价的金额（method-为1的时候生效）;
 * @property int  $zero_line_amount zeroLineAmount 包邮金额;
 * @property string $remark  ;
 * @property  $create_time createTime ;
 * @property  $update_time updateTime ;
 */
class ShopFreightModel extends Base{
    public static function tableName()
    {
        return '{{%shop_freight}}';
    }

    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['id'],'required','on' => [ 'update' ]],
            [['id','add_post_period','remark',],'string','on' => [ 'default','update']],
            [['method','post_amount','add_post_amount','distance_price','zero_line_amount', ], 'integer','on' => [ 'default','update']],
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


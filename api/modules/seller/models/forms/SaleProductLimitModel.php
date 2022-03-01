<?php
namespace api\modules\seller\models\forms;
/**
 * This is the model class for table "{{%sale_product_limit}}".
 *
 * @property string $id  md5(userid,pro+strategyId);
 * @property string $key_id keyId md5(productId,startDay);
 * @property string $user_id userId 用户Id;
 * @property string $product_id productId 商品Id;
 * @property int  $number  购买的数量;
 * @property int  $limit_number limitNumber 限购数量;
 * @property int  $created_at createdAt ;
 * @property int  $updated_at updatedAt ;
 */
class SaleProductLimitModel extends Base{
    public static function tableName()
    {
        return '{{%sale_product_limit}}';
    }

    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['id'],'required','on' => [ 'update' ]],
            [['id','key_id','user_id','product_id',],'string','on' => [ 'default','update']],
            [['number','limit_number', ], 'integer','on' => [ 'default','update']],
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


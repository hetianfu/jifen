<?php
namespace api\modules\seller\models\forms;
/**
 * This is the model class for table "{{%product_sku_result}}".
 *
 * @property  $id  商品Id;
 * @property string $merchant_id merchantId ;
 * @property string $tag_snap tagSnap 标签列表，包括名称，显示;
 * @property string $tag_detail_snap tagDetailSnap 标签详情，名称，价格，库存，缩略图，是否显示;
 * @property int  $created_at createdAt ;
 * @property int  $updated_at updatedAt ;
 */
class ProductSkuResultModel extends Base{
    public static function tableName()
    {
        return '{{%product_sku_result}}';
    }

    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['id'],'required','on' => [ 'update' ]],
            [[ 'merchant_id', 'tag_snap','tag_detail_snap', ],'string','on' => [ 'default','update']],
            [[ ], 'integer','on' => [ 'default','update']],
        ], $rules);
    }


    public function fields(){
        $fields = parent::fields();
        $fields['tagSnap']=function (){
            return json_decode($this->tag_snap);
        };
        $fields['tagDetailSnap']= function (){
            return json_decode($this->tag_detail_snap);
        };
        return $fields;
    }

    public function setAttributes($values, $safeOnly = true) {

        parent::setAttributes($values, $safeOnly);
    }

}


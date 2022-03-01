<?php
namespace api\modules\seller\models\forms;
/**
 * This is the model class for table "{{%store_client_category}}".
 *
 * @property  $id
 * @property string $merchant_id merchantId 商户Id;
 * @property  $pid  上级;
 * @property string $name  名称;
 * @property int  $show_order showOrder 排序;
 * @property int  $created_at createdAt ;
 * @property int  $updated_at updatedAt ;
 */
class StoreClientCategoryModel extends Base{
    public static function tableName()
    {
        return '{{%store_client_category}}';
    }

    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['id'],'required','on' => [ 'update' ]],
            [['merchant_id','name',],'string','on' => [ 'default','update']],
            [['show_order', ], 'integer','on' => [ 'default','update']],
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


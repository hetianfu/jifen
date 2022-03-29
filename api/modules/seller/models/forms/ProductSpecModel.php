<?php
namespace api\modules\seller\models\forms;
/**
 * This is the model class for table "{{%product_spec}}".
 *
 * @property  $id  ;
 * @property string $merchant_id ;
 * @property string $name  商品属性名称;
 * @property string $spec_tag specTag 规格镜像;
 * @property string $spec_tag_detail specTagDetail 规格属性;
 * @property int  $show_order showOrder 商品属性排序;
 * @property int  $created_at createdAt ;
 * @property int  $updated_at updatedAt ;
 */
class ProductSpecModel extends Base{
    public static function tableName()
    {
        return '{{%product_spec}}';
    }

    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['id'],'required','on' => [ 'update' ]],
            [['merchant_id','name','spec_tag','spec_tag_detail',],'string','on' => [ 'default','update']],
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


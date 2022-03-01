<?php
namespace api\modules\mobile\models\forms;
use fanyou\enums\StatusEnum;

/**
 * This is the model class for table "{{%product_category}}".
 *
 * @property  $id  ;
 * @property string $category_id categoryId 分类Id;
 * @property string $product_id productId 商品Id;
 * @property int  $created_at createdAt 创建时间;
 * @property int  $updated_at updatedAt 更新时间;
 */
class ProductCategoryModel extends Base{
    public static function tableName()
    {
        return '{{%product_category}}';
    }

    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['id'],'required','on' => [ 'update' ]],
            [['category_id','product_id',],'string','on' => [ 'default','update']],
            [[ ], 'integer','on' => [ 'default','update']],
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


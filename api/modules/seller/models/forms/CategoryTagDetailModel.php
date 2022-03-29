<?php
namespace api\modules\seller\models\forms;
/**
 * This is the model class for table "{{%category_tag_detail}}".
 *
 * @property  $id  ;
 * @property string $spec_id specId 规格Id;
 * @property string $tag_id tagId 分类标签Id;
 * @property string $name  模型属性值;
 * @property int  $show_order showOrder 排序;
 * @property  $created_at createdAt ;
 * @property  $updated_at updatedAt ;
 */
class CategoryTagDetailModel extends Base{
    public static function tableName()
    {
        return '{{%category_tag_detail}}';
    }

    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['id'],'required','on' => [ 'update' ]],
            [['tag_id','spec_id','name',],'string','on' => [ 'default','update']],
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


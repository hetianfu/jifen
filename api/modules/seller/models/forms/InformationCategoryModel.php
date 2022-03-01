<?php
namespace api\modules\seller\models\forms;
/**
 * This is the model class for table "{{%information_category}}".
 *
 * @property int  $id  文章分类id;
 * @property int  $pid  父级ID;
 * @property string $title  文章分类标题;
 * @property string $info  文章分类简介;
 * @property string $pic  文章分类图片;
 * @property  $status  状态0未发布 1已发布;
 * @property int  $show_order showOrder 排序;
 * @property int  $created_at createdAt 添加时间;
 * @property int  $updated_at updatedAt ;
 */
class InformationCategoryModel extends Base{
    public static function tableName()
    {
        return '{{%information_category}}';
    }

    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['id'],'required','on' => [ 'update' ]],
            [['title','info','pic',],'string','on' => [ 'default','update']],
            [['id','pid','show_order','status' ,'is_system'], 'integer','on' => [ 'default','update']],
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


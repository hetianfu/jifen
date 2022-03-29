<?php
namespace api\modules\seller\models\forms;
/**
 * This is the model class for table "{{%category_tag}}".
 *
 * @property  $id  ;
 * @property string $shop_id shopId ;
 * @property string $name  商品属性名称;
 * @property int  $choose  0单选，1多选;
 * @property int  $necessary  0非必选，1必选;
 * @property int  $show_order showOrder 商品属性排序;
 * @property  $create_time createTime ;
 * @property  $update_time updateTime ;
 */
class CategoryTagModel extends Base{
    public static function tableName()
    {
        return '{{%category_tag}}';
    }

    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['id'],'required','on' => [ 'update' ]],
            [['shop_id', 'name',],'string','on' => [ 'default','update']],
            [['choose','necessary','show_order', ], 'integer','on' => [ 'default','update']],
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


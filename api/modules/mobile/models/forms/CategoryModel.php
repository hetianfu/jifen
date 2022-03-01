<?php
namespace api\modules\mobile\models\forms;
/**
 * This is the model class for table "{{%category}}".
 *
 * @property  $id  ;
 * @property string $name  分类名称;
 * @property string $merchant_id merchantId 商户Id;
 * @property int  $show_order showOrder 排序;
 * @property string $brand_id brandId 品牌代码;
 * @property string $brand_name brandName 品牌;
 * @property string $style  分类样式（前端显示预留）;
 * @property string $parent_id parentId 上级分类id-为0则表示是顶级;
 * @property string $level  grandfatherId.partnetId   层级关系;
 * @property int  $status  分类状态 1显示 0不显示
 * @property string $detail  分类描述;
 * @property  $created_at   创建时间;
 * @property  $updated_at   更新时间;
 */
class CategoryModel extends Base{
    public static function tableName()
    {
        return '{{%category}}';
    }
    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['id'],'required','on' => [ 'update' ]],
            [['id','pic','name', 'merchant_id','brand_id','brand_name','style','parent_id','level','detail',],'string','on' => [ 'default','update']],
            [['show_order','status', ], 'integer','on' => [ 'default','update']],
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


<?php
namespace api\modules\seller\models\forms;

/**
 * This is the model class for table "{{%shop_department}}".
 *
 * @property string $id  部门id;
 * @property string $name  部门名称;
 * @property string $shop_id shopId 店铺Id;
 * @property string $level  层级关系，以 .  分隔;
 * @property string $parent_id parentId 上级Id;
 * @property string $lead_name leadName 负责人;
 * @property string $lead_phone leadPhone 部门负责人电话;
 * @property string $detail  部门描述;
 * @property string $snap  存储镜像;
 * @property int  $create_time createTime ;
 * @property int  $update_time updateTime ;
 */
class ShopDepartmentModel extends Base {
    public static function tableName()
    {
        return '{{%shop_department}}';
    }

    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['id'],'required','on' => [ 'update' ]],
            [['name','level','detail','snap','lead_name','lead_phone'],'string','on' => [ 'default','update']],
            [['id', ], 'integer','on' => [ 'default','update']],
        ], $rules);
    }


    public function fields(){
        $fields = parent::fields();
        return $fields;
    }

    public function setAttributes($values, $safeOnly = true) {

        parent::setAttributes($values, false);

    }

}


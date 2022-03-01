<?php
namespace api\modules\seller\models;
use yii\db\ActiveRecord;

class ShopDepartmentResult  extends ActiveRecord{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%shop_department}}';
    }

    public function fields() {
        $fields = parent::fields();
        $fields['shopId'] = 'shop_id';
        $fields['parentId'] = 'parent_id';
        $fields['leadName'] = 'lead_name';
        $fields['leadPhone'] = 'lead_phone';
        $fields['createTime'] = 'create_time';
        $fields['updateTime'] = 'update_time';
        unset( $fields['shop_id'],$fields['parent_id'],$fields['lead_name'],$fields['lead_phone'],$fields['create_time'],$fields['update_time'] );
        return $fields;
    }
}

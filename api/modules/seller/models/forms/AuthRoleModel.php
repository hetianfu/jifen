<?php
namespace api\modules\seller\models\forms;
/**
 * This is the model class for table "{{%common_auth_role}}".
 *
 * @property int  $id  主键;
 * @property int  $merchant_id merchantId 商户id;
 * @property string $shop_id shopId 店铺Id;
 * @property string $title  标题;
 * @property string $app_id appId 应用;
 * @property int  $pid  上级id;
 * @property  $level  级别;
 * @property int  $sort  排序;
 * @property string $tree  树;
 * @property int  $status  状态[-1:删除;0:禁用;1启用];
 * @property int  $created_at createdAt 添加时间;
 * @property int  $updated_at updatedAt 修改时间;
 * @property false|string extend_item
 */
class AuthRoleModel extends Base{
    public static function tableName()
    {
        return '{{%common_auth_role}}';
    }

    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['id'],'required','on' => [ 'update' ]],
            [['shop_id','title','app_id','tree',],'string','on' => [ 'default','update']],
            [['id','merchant_id','pid','sort','status','created_at','updated_at', ], 'integer','on' => [ 'default','update']],
            [['extend_item'],'safe','on' => [ 'default','update']],
        ], $rules);
    }


    public function fields(){
        $fields = parent::fields();

        $fields['parentId']='pid';
        unset( $fields['pid']);
        return $fields;
    }

    public function setAttributes($values, $safeOnly = true) {
        $values['parentId']&&$values['pid']=$values['parentId'];

        parent::setAttributes($values, $safeOnly);
    }
}


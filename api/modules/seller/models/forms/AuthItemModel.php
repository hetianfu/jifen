<?php
namespace api\modules\seller\models\forms;
/**
 * This is the model class for table "{{%common_auth_item}}".
 *
 * @property int  $id  ;
 * @property string $shop_id shopId 店铺Id;
 * @property string $merchant_id merchantId 商户Id;
 * @property string $title  标题;
 * @property string $name  别名;
 * @property string key  属性key;
 * @property string $service_path  后台路由;
 * @property string $type  类别;
 * @property string $redirect   插件名称;
 * @property int  $pid  父级id;
 * @property int  $level  级别;
 * @property int  $is_menu isMenu 是否菜单;
 * @property int  $sort  排序;
 * @property string $tree  树;
 * @property int  $status  状态[-1:删除;0:禁用;1启用];
 * @property int  $created_at createdAt ;
 * @property int  $updated_at updatedAt ;
 * @property mixed show
 * @property mixed icon
 */
class AuthItemModel extends Base{
    public static function tableName()
    {
        return '{{%common_auth_item}}';
    }

    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['id'],'required','on' => [ 'update' ]],
            [['shop_id','path','title','icon','key','name','service_path','request_method','component','redirect','type','tree',],'string','on' => [ 'default','update']],
            [['id','pid','level','show','is_menu','sort','status'  ], 'integer','on' => [ 'default','update']],
            [['merchant_id',  ], 'safe','on' => [ 'default','update']],
        ], $rules);
    }


    public function fields(){
        $fields = parent::fields();
        $fields['parentId']='pid';
        $fields['meta']= function (){
            return ['show'=>$this->show,'title'=>$this->title,'icon'=>$this->icon,'key'=>$this->key];
        };
        unset( $fields['pid']);

        return $fields;
    }

    public function setAttributes($values, $safeOnly = true)
    {

        $values['parent_id']&&$values['pid']=$values['parent_id'];
        if (!empty($values['meta'])) {
            $values['show'] = $values['meta']['show'];
            $values['title'] =$values['meta']['title'];
            $values['icon'] =$values['meta']['icon'];
            $values['key'] =$values['meta']['key'];
         }
        unset($values['meta']);
        parent::setAttributes($values, $safeOnly);
    }

}


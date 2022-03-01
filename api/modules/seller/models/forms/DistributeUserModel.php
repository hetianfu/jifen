<?php
namespace api\modules\seller\models\forms;
/**
 * This is the model class for table "{{%distribute_user}}".
 *
 * @property string $id  用户Id;
 * @property  $identify  用户身份 0-经销商 1-服务中心 2-市代;
 * @property string $parent_id parentId 上级Id;
 * @property  $status  0-禁用 1启用;
 * @property int  $created_at createdAt ;
 * @property int  $updated_at updatedAt ;
 */
class DistributeUserModel extends Base{
    public static function tableName()
    {
        return '{{%distribute_user}}';
    }
    public $partner;
    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['id'],'required','on' => [ 'update' ]],
            [['id',],'string','on' => [ 'default','update']],
            [['status' ,'identify'], 'integer','on' => [ 'default','update']],
        ], $rules);
    }


    public function fields(){
        $fields = parent::fields();
        isset($this->userInfo)&&$fields['userInfo']='userInfo';
        !empty($this->partner)&&$fields['partner']='partner';
        return $fields;
    }

    public function setAttributes($values, $safeOnly = true) {
        parent::setAttributes($values, $safeOnly);
    }


    public function  getUserInfo(){
        return $this->hasOne( UserInfoModel::class,['id'=>'id'])->select(['id','head_img','nick_name','is_sales_person' ]);
    }


}


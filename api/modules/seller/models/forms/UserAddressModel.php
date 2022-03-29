<?php
namespace api\modules\seller\models\forms;
/**
 * This is the model class for table "{{%user_address}}".
 *
 * @property string $id  地址Id;
 * @property string $user_id userId 用户Id;
 * @property string $name  用户真实姓名;
 * @property string $telephone  联系电话;
 * @property string $other_phone otherPhone 备用电话;
 * @property string $city  所属城市;
 * @property string $lat  ;
 * @property string $lng  ;
 * @property string $street  街道地址;
 * @property string $room  房间号;
 * @property string $address_type addressType 地址类型;
 * @property string $address_snap addressSnap 地址快照;
 * @property  $create_time createTime ;
 * @property  $update_time updateTime ;
 */
class UserAddressModel extends Base{
    public static function tableName()
    {
        return '{{%user_address}}';
    }

    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['id'],'required','on' => [ 'update' ]],
            [['id','user_id','name','telephone','other_phone','city','lat','lng','street','room','detail','address_type','address_snap',],'string','on' => [ 'default','update']],
            [[ ], 'integer','on' => [ 'default','update']],
            [['create_time','update_time',],'safe','on' => [ 'default','update']],
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


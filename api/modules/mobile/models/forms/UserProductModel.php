<?php
namespace api\modules\mobile\models\forms;
/**
 * This is the model class for table "{{%user_product}}".
 *
 * @property  $id  用户真实Id;
 * @property  $product_id productId 商品Id;
 * @property string $user_id userId 用户Id;
 * @property int  $number  购买次数;
 * @property string $product_name productName 商品名称;
 * @property int  $status  ;
 * @property int  $created_at createdAt ;
 * @property int  $updated_at updatedAt ;
 */
class UserProductModel extends Base{

    public static function tableName()
    {
        return '{{%user_product}}';
    }

    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['id'],'required','on' => [ 'update' ]],
            [['user_id','product_name','product_id'],'string','on' => [ 'default','update']],
            [['number','status', ], 'integer','on' => [ 'default','update']],
            [['connect_snap', ], 'safe','on' => [ 'default','update']],

        ], $rules);
    }
    public function fields(){
        $fields = parent::fields();
//        if(!empty($this->userInfo)){
//        $fields['userInfo']='userInfo';
//        }
        isset($this->connect_snap)&&$fields['connectSnap']=  function (){
            return json_decode($this->connect_snap);
        };

        isset($this->user_id)&&$fields['userId']='user_id';
        isset($this->product_name)&&$fields['productName']='product_name';
        isset($this->product_id)&&$fields['productId']='product_id';
        unset($fields['product_id'],$fields['product_name']);
        return $fields;
    }

    public function setAttributes($values, $safeOnly = true) {
        parent::setAttributes($values, $safeOnly);
    }

//    public function getUserInfo( ) {
//        return $this->hasOne( UserInfoModel::class,['id'=>'user_id'])->select(['id','nick_name','head_img','telephone' ]);
//
//    }
}


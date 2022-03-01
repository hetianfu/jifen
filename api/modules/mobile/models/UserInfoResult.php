<?php
namespace api\modules\mobile\models;
/**
 * This is the model class for table "{{%user_info}}".
 *
 * @property string $freeCount  免费卡数量;
 * @property int  $shoppingCart  购物车;
 * @property int  $userInfo  用户信息;
 *@property int  $channel  用户信息;
 * */
class UserInfoResult {
    public $freeCount=0;
    public $shoppingCart=[];
    public $shoppingCartCount=0;
    public $userInfo;
    public $token;
    public $vip;
    public $channel;
//    public function rules(){
//        $rules = parent::rules();
//        return array_merge([
//            [['id'],'required','on' => [ 'update' ]],
//            [['id','level_id','head_img','nick_name','telephone','birth_day', 'code','status','parent_id','parent_type','project_id','proxy_id','source_path','city',],'string','on' => [ 'default','update']],
//            [['freeCount','shoppingCartCount'  ], 'integer','on' => [ 'default','update']],
//            [['shoppingCart','userInfo'  ], 'safe','on' => [ 'default','update']],
//
//        ], $rules);
//    }
//
//
//    public function fields(){
//        $fields = parent::fields();
//        return $fields;
//    }


}


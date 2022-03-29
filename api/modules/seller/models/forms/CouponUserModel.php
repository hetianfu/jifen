<?php
namespace api\modules\seller\models\forms;
/**
 * This is the model class for table "{{%coupon_user}}".
 *
 * @property  $id  ;
 * @property string $title  优惠券名称;
 * @property string $merchant_id  商户;
 * @property string $type;
 * @property string $type_relation_id;
 * @property int  $addtime  领取时间;
 * @property int  $fromtime  开始使用时间;
 * @property int  $totime  结束时间;
 * @property int  $amount  优惠券面值;
 * @property int  $limit_amount limitAmount 最低消费;
 * @property int  $status  0-可使用 1已使用    -1待使用;
 * @property string $get_method getMethod HAND-手动领取  MANAGER -后台发放;
 * @property string $editor  发布人姓名;
 * @property string $user_id userId 用户Id;
 * @property string $user_snap userSnap 领取人镜像（电话）;
 * @property int  $created_at createdAt ;
 * @property int  $updated_at updatedAt ;
 * @property string $remark  ;
 * @property array|mixed coupon_id
 */
class CouponUserModel extends Base{
    public static function tableName()
    {
        return '{{%coupon_user}}';
    }

    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['id'],'required','on' => [ 'update' ]],
            [['title','type','type_relation_id','merchant_id','get_method','editor','user_id','user_snap','coupon_id','remark',],'string','on' => [ 'default','update']],
            [[ 'fromtime','totime','amount','limit_amount','status', ], 'integer','on' => [ 'default','update']],
            [[ 'amount','limit_amount' ], 'number','on' => [ 'default','update']],
        ], $rules);
    }


    public function fields(){
        $fields = parent::fields();
        isset($this->userSnap)&&$fields['userSnap']='userSnap';
        return $fields;
    }

    public function setAttributes($values, $safeOnly = true) {
        parent::setAttributes($values, $safeOnly);
    }

    public function getUserSnap(){
        return $this->hasOne( UserInfoModel::class,['id'=>'user_id'])->select(['id','nick_name','code','head_img','telephone' ]);
    }

}


<?php
namespace api\modules\mobile\models\forms;
/**
 * This is the model class for table "{{%coupon_user}}".
 *
 * @property  $id  ;
 * @property int  $coupon_id   couponId领取时间;
 * @property string $title  优惠券名称;
 * @property string $merchant_id  商户;
 * @property int  $fromtime  开始使用时间;
 * @property int  $totime  结束时间;
 * @property int  $amount  优惠券面值;
 * @property int  $limit_amount limitAmount 最低消费;
 * @property int  $status  0-可使用 1已使用    -1待使用;
 * @property int $is_del  是否已删除
 * @property string $get_method getMethod HAND-手动领取  MANAGER -后台发放;
 * @property string $editor  发布人姓名;
 * @property string $user_id userId 用户Id;
 * @property string $user_snap userSnap 领取人镜像（电话）;
 * @property int  $created_at createdAt ;
 * @property int  $updated_at updatedAt ;
 * @property string $remark  ;
 * @property  int $type
 * @property string $type_relation_id
 */
class CouponUserModel extends Base{

    public $is_limit;
    public static function tableName()
    {
        return '{{%coupon_user}}';
    }

    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['id'],'required','on' => [ 'update' ]],
            [['title','type','type_relation_id','get_method','editor','user_id','user_snap','remark',],'string','on' => [ 'default','update']],
            [[ 'fromtime','totime', 'status','is_del' ], 'integer','on' => [ 'default','update']],
            [['id','coupon_id','amount','limit_amount' ], 'number','on' => [ 'default','update']],
            [['merchant_id', ], 'safe','on' => [ 'default','update']],

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


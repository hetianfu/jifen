<?php
namespace api\modules\seller\models\forms;

/**
 * This is the model class for table "{{%product_reply}}".
 *
 * @property  $id  评论ID;
 * @property int  $user_id userId 用户ID;
 * @property int  $order_id orderId 订单ID;
 * @property int  $product_id productId 产品id;
 * @property string $reply_type replyType 某种商品类型(普通商品、秒杀商品）;
 * @property  $product_score productScore 商品分数;
 * @property  $service_score serviceScore 服务分数;
 * @property string $comment  评论内容;
 * @property string $pics  评论图片;
 * @property string $merchant_reply_content merchantReplyContent 管理员回复内容;
 * @property int  $merchant_reply_time merchantReplyTime 管理员回复时间;
 * @property  $is_del isDel 0未删除1已删除   ;
 * @property  $is_reply isReply 0未回复1已回复;
 * @property int  $created_at createdAt 评论时间;
 * @property int  $updated_at updatedAt 更新时间;
 * @property false|mixed|string|null product_snap
 */
class ProductReplyModel extends Base{
    public static function tableName()
    {
        return '{{%product_reply}}';
    }

    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['id'],'required','on' => [ 'update' ]],
            [[ 'reply_type','order_id','comment','pics','merchant_reply_content','user_id','user_name','portrait'],'string','on' => [ 'default','update']],
            [['product_id','merchant_reply_time' ], 'number','on' => [ 'default','update']],
            [['is_del','is_reply' ,'status','is_recommend','show_order'  ], 'integer','on' => [ 'default','update']],
            [['pics' ,'product_snap'  ], 'safe','on' => [ 'default','update']],

        ], $rules);
    }


    public function fields(){
        $fields = parent::fields();
        isset($this->productInfo)&&$fields['productInfo']='productInfo';
        isset($this->userInfo)&&$fields['userInfo']='userInfo';

        isset($this->pics) && $fields['pics'] =function (){
            return json_decode($this->pics);
        };
        return $fields;
    }

    public function setAttributes($values, $safeOnly = true) {

        parent::setAttributes($values, $safeOnly);
    }

    public function  getUserInfo(){
        return $this->hasOne( UserInfoModel::class,['id'=>'user_id'])->select(['id','head_img','nick_name','is_sales_person' ]);
    }

    public function getProductInfo()
    {
        return $this->hasOne(ProductModel::class, ['id' => 'product_id'])->select(['id', 'name', 'cover_img', 'sale_price']);
    }
}


<?php

namespace api\modules\mobile\models\forms;
/**
 * This is the model class for table "{{%product_reply}}".
 *
 * @property  $id  评论ID;
 * @property int $user_id userId 用户ID;
 * @property int $order_id orderId 订单ID;
 * @property int $product_id productId 产品id;
 * @property string $reply_type replyType 某种商品类型(普通商品、秒杀商品）;
 * @property  $product_score productScore 商品分数;
 * @property  $service_score serviceScore 服务分数;
 * @property string $comment  评论内容;
 * @property string $pics  评论图片;
 * @property string $merchant_reply_content merchantReplyContent 管理员回复内容;
 * @property int $merchant_reply_time merchantReplyTime 管理员回复时间;
 * @property  $is_del isDel 0未删除1已删除   ;
 * @property  $is_reply isReply 0未回复1已回复;
 * @property int $created_at createdAt 评论时间;
 * @property int $updated_at updatedAt 更新时间;
 * @property mixed product_snap
 */
class ProductReplyModel extends Base
{
    public static function tableName()
    {
        return '{{%product_reply}}';
    }

    public function rules()
    {
        $rules = parent::rules();
        return array_merge([
            [['id'], 'required', 'on' => ['update']],
            [['reply_type', 'order_id', 'user_id', 'comment', 'merchant_reply_content',], 'string', 'on' => ['default', 'update']],
            [['merchant_reply_time',], 'integer', 'on' => ['default', 'update']],
            [['is_reply', 'is_recommend', 'show_order','is_del','product_score','status'], 'integer', 'on' => ['default', 'update']],
            [['pics', 'product_id','product_snap'], 'safe', 'on' => ['default', 'update']],
        ], $rules);
    }


    public function fields()
    {
        $fields = parent::fields();
        isset($this->is_recommend) && $fields['isRecommend'] = 'is_recommend';
        isset($this->reply_type) && $fields['replyType'] = 'reply_type';
        isset($this->product_id) && $fields['productId'] = 'product_id';

        isset($this->user_id) && $fields['userId'] = 'user_id';
        isset($this->product_score) && $fields['productScore'] = 'product_score';
        isset($this->is_reply) && $fields['isReply'] = 'is_reply';
        isset($this->merchant_reply_content) && $fields['merchantReplyContent'] = 'merchant_reply_content';
        isset($this->merchant_reply_time) && $fields['merchantReplyTime'] = 'merchant_reply_time';
        isset($this->is_del) && $fields['isDel'] = 'is_del';

        isset($this->productInfo) && $fields['productInfo'] = 'productInfo';
        isset($this->userInfo) && $fields['userInfo'] = 'userInfo';
        isset($this->pics) && $fields['pics'] =function (){
            return json_decode($this->pics);
        };
        isset($this->product_snap) && $fields['product_snap'] =function (){
            return json_decode($this->product_snap);
        };
        unset($fields['product_snap'],$fields['is_recommend'],$fields['reply_type'],$fields['user_id'],$fields['merchant_reply_content'],$fields['merchant_reply_time']);
        return $fields;
    }

    public function setAttributes($values, $safeOnly = true)
    {

       isset($values['pics'])&&$values['pics']=json_encode($values['pics'],JSON_UNESCAPED_UNICODE);
       isset($values['product_snap'])&&$values['product_snap']=json_encode($values['product_snap'],JSON_UNESCAPED_UNICODE);
        parent::setAttributes($values, $safeOnly);
    }

    public function getUserInfo()
    {
        return $this->hasOne(UserInfoModel::class, ['id' => 'user_id'])->select(['id', 'head_img', 'nick_name', 'is_sales_person']);
    }

    public function getProductInfo()
    {
        return $this->hasOne(ProductModel::class, ['id' => 'product_id'])->select(['id','type', 'name', 'images','is_on_sale', 'sale_price','sale_strategy']);
    }
}


<?php
namespace api\modules\seller\models\forms;
/**
 * This is the model class for table "{{%information}}".
 *
 * @property  $id  ;
 * @property  $merchant_id merchantId ;
 * @property string $relevant_shop_id relevantShopId 关联店铺Id;
 * @property string $relevant_product_id relevantProductId 关联商品Id;
 * @property string $city  ;
 * @property string $title  标题;
 * @property string $sub_title subTitle 副标题（简介）;
 * @property string $share_title shareTitle 分享标题;
 * @property int  $read_number readNumber  阅读次数;
 * @property int  $collect_number collectNumber 收藏次数;
 * @property string $publish_id publishId 发布人Id;
 * @property string $publish_snap publishSnap  发布人镜像;
 * @property string $content  内容;
 * @property string $cover_img coverImg  封面图;
 * @property string $images  ;
 * @property string $video_cover_img videoCoverImg 视频封面;
 * @property string $video  视频地址;
 * @property int  $is_show isShow 是否展示;
 * @property string $user_snap userSnap 用户镜像;
 * @property  $publish_time publishTime 发布时间;
 * @property int  $show_order showOrder 排序;
 *
 */
class InformationModel extends Base{
    public static function tableName()
    {
        return '{{%information}}';
    }

    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['id'],'required','on' => [ 'update' ]],
            [['id','relevant_shop_id','relevant_product_id','city','title','sub_title','share_title',
                'publish_id','publish_snap','content','cover_img','images','video_cover_img','video','user_snap',],'string','on' => [ 'default','update']],
            [['read_number','collect_number','is_show','show_order' ,'merchant_id','cid'], 'integer','on' => [ 'default','update']],
        ], $rules);
    }


    public function fields(){
        $fields = parent::fields();
        isset($this->relevant_product_id)&&$fields['relevantProductId']='relevant_product_id';
        isset($this->relevant_shop_id)&&$fields['relevantShopId']='relevant_shop_id';
        isset($this->collect_number)&&$fields['collectNumber']='collect_number';
        isset($this->cover_img)&&$fields['coverImg']='cover_img';
        isset($this->read_number)&&$fields['readNumber']='read_number';
        isset($this->created_at)&&$fields['createdAt']='created_at';
        isset($this->sub_title)&&$fields['subTitle']='sub_title';
        isset($this->share_title)&&$fields['shareTitle']='share_title';

        unset($fields['relevant_product_id'],$fields['relevant_shop_id'],$fields['collect_number'],$fields['cover_img'],$fields['read_number']
            ,$fields['created_at']  ,$fields['sub_title'] ,$fields['share_title']
    );
         return $fields;
    }

    public function setAttributes($values, $safeOnly = true) {

        parent::setAttributes($values, $safeOnly);
    }
}


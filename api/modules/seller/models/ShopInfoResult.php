<?php

namespace api\modules\seller\models;
use yii\db\ActiveRecord;

/**
 * @property mixed view
 * @property mixed logo
 * @property mixed coverImg
 * @author E-mail: Administrator@qq.com
 * Create Time: 2019/03/27
 */
class ShopInfoResult extends ActiveRecord{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%shop_info}}';
    }

    public function fields()
    {
        $fields = parent::fields();
//        $fields['shopName']='shop_name';
//        $fields['shareImg']='share_img';
//        $fields['coverImg']='cover_img';
//        $fields['postStatus']='post_status';
//        $fields['postTime']='post_time';
//        $fields['postStartTime']='post_start_time';
//        $fields['postEndTime']='post_end_time';
//        $fields['businessTime']='business_time';
//        $fields['onSale']= 'on_sale';
//        if(!empty($this->logo)) {
//            $fields['logo'] = function () {
//                return json_decode($this->logo, true);
//            };
//        }
//        if(!empty($this->view)) {
//            $fields['view'] = function () {
//                return json_decode($this->view, true);
//            };
//        }
//        if(!empty($this->coverImg)) {
//            $fields['coverImg'] = function () {
//                return json_decode($this->coverImg, true);
//            };
//        }
//        unset($fields['shop_name'],$fields['account_name'],$fields['share_img'],$fields['cover_img'],
//        $fields['post_status'], $fields['post_start_time'], $fields['post_end_time'], $fields['business_time'],
//            $fields['on_sale'],
//        );
        return $fields;
    } 
    
	
  
}



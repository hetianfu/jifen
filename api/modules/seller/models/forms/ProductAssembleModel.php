<?php
namespace api\modules\seller\models\forms;
/**
 * This is the model class for table "{{%product_assemble}}".
 *
 * @property int  $id id ;
 * @property int  $goods_id goodsId 商品id;
 * @property int  $goods_assemble_id goodsAssembleId 拼团配置表ID;
 * @property int  $user_id userId 开团人id;
 * @property int  $assemble_num assembleNum 拼团总人数;
 * @property int  $put_assemble putAssemble 已拼团人数;
 * @property int  $state  1拼团中，2拼团成功，3拼团失败，4已退款;
 * @property  $create_time createTime 开团时间;
 * @property  $end_time endTime 拼团截止时间;
 * @property int  $is_del isDel 1删除;
 */
class ProductAssembleModel extends Base{
    public static function tableName()
    {
        return '{{%product_assemble}}';
    }

    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['id'],'required','on' => [ 'update' ]],
            [[],'string','on' => [ 'default','update']],
            [['assemble_id','goods_id','goods_assemble_id','user_id','assemble_num','put_assemble','state','is_del', ], 'integer','on' => [ 'default','update']],
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


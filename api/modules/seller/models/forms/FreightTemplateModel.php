<?php
namespace api\modules\seller\models\forms;
/**
 * This is the model class for table "{{%freight_template}}".
 *
 * @property int  $id  模版Id;
 * @property string $name  模版名称;
 * @property  $type  0-按件数 1按体积 2按重量;
 * @property  $is_free_post isFreePost 0-不包邮 1包邮;
 * @property int  $merchant_id merchantId 商户Id;
 * @property string $freight_snap freightSnap 配送区域及运费;
 * @property string $post_snap postSnap 指定包邮;
 * @property int  $sort  排序;
 * @property string $remark  备注;
 * @property int  $created_at createdAt ;
 * @property int  $updated_at updatedAt ;
 */
class FreightTemplateModel extends Base{
    public static function tableName()
    {
        return '{{%freight_template}}';
    }

    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['id'],'required','on' => [ 'update' ]],
            [['name','remark',],'string','on' => [ 'default','update']],
            [['id','merchant_id','sort','type','is_free_post' ], 'integer','on' => [ 'default','update']],
            [['freight_snap','post_snap', ], 'safe','on' => [ 'default','update']],
        ], $rules);
    }


    public function fields(){
        $fields = parent::fields();
        if (!empty($this->freight_snap)) {
            $fields['freight_snap'] = function () {
                return json_decode($this->freight_snap);
            };
        }
        if (!empty($this->post_snap)) {
            $fields['post_snap'] = function () {
                return json_decode($this->post_snap);
            };
        }
        return $fields;
    }

    public function setAttributes($values, $safeOnly = true) {
        isset($values['freight_snap']) && $values['freight_snap'] = json_encode($values['freight_snap'],JSON_UNESCAPED_UNICODE);
        isset($values['post_snap']) && $values['post_snap'] = json_encode($values['post_snap'],JSON_UNESCAPED_UNICODE);
        parent::setAttributes($values, $safeOnly);
    }
}


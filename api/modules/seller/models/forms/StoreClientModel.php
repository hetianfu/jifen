<?php
namespace api\modules\seller\models\forms;
/**
 * This is the model class for table "{{%store_client}}".
 *
 * @property  $id  存储云OSS/QINIU/LOCAL;
 * @property string $merchant_id merchantId 商户Id;
 * @property $category_id;
 * @property string $url  图片地址;
 * @property string $key  键值;
 * @property string $file_type fileType 类型 img/doc/;
 * @property string $type  存储云  OSS/QINIU/LOCAL;
 * @property int  $status  状态;
 * @property $size;
 * @property int  $created_at createdAt ;
 * @property int  $updated_at updatedAt ;
 * @property false|mixed|string|null name
 */
class StoreClientModel extends Base{
    public static function tableName()
    {
        return '{{%store_client}}';
    }

    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['id'],'required','on' => [ 'update' ]],
            [['url','key','file_type','type','name' ],'string','on' => [ 'default','update']],
            [['status','category_id' ], 'integer','on' => [ 'default','update']],
            [['merchant_id','size' ], 'safe','on' => [ 'default','update']],
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


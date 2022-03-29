<?php

namespace api\modules\mobile\models\forms;
/**
 * This is the model class for table "{{%pink_config}}".
 *
 * @property int $id  ;
 * @property int $cid  拼团产品id;
 * @property int $product_id productId 产品id;
 * @property int $people  拼团总人数;
 * @property int $start_time startTime 开始时间;
 * @property int $end_time endTime 结束时间;
 * @property int $remain_time remainTime 持续时间（秒）;
 * @property int $created_at createdAt ;
 * @property int $updated_at updatedAt ;
 */
class PinkConfigModel extends Base
{
    public static function tableName()
    {
        return '{{%pink_config}}';
    }

    public $hasPart;
    public $isSamePink;

    public function rules()
    {
        $rules = parent::rules();
        return array_merge([
            [['id'], 'required', 'on' => ['update']],
            [[], 'string', 'on' => ['default', 'update']],
            [['id', 'product_id', 'people', 'start_time', 'end_time', 'remain_time',], 'integer', 'on' => ['default', 'update']],
        ], $rules);
    }
    public function fields()
    {
        $fields = parent::fields();
        !is_null($this->isSamePink) && $fields['isSamePink'] = 'isSamePink';
        !is_null($this->hasPart) && $fields['hasPart'] = 'hasPart';
        return $fields;
    }

    public function setAttributes($values, $safeOnly = true)
    {
        parent::setAttributes($values, $safeOnly);
    }
}


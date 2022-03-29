<?php
namespace api\modules\mobile\models\forms;

/**
 * @property int id
 * @author E-mail: Administrator@qq.com
 * Create Time: 2019/03/27
 */
class ShopInfoQuery extends BaseQuery
{
    public $id;
    public function rules()
    {
        $rules = parent::rules();
        return array_merge([
            [[ 'id'  ],'safe'],
        ], $rules);
    }
    public function fields()
    {
        $fields  = parent::fields();
        isset($this->id) &&$fields['id'] = 'id';

        return $fields;
    }

}



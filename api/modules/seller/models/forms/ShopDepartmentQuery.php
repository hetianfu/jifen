<?php

namespace api\modules\seller\models\forms;


/**
 * @property string shopId
 * @author E-mail: Administrator@qq.com
 * Create Time: 2019/03/27
 */
class ShopDepartmentQuery extends BaseQuery
{
    public $shop_id;
    public function rules()
    {
        $rules = parent::rules();
        return array_merge([
            [['shop_id'], 'string'],
        ], $rules);
    }
    public function fields()
    {
        $fields  = parent::fields();
        isset($this->shop_id) &&$fields['shop_id'] = 'shop_id';
        return $fields;
    }

}



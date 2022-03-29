<?php

namespace api\modules\seller\models\forms;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-05-08
 */
class ProductStockQuery extends BaseQuery
{
    public $product_id;
    public $category_id;

    public function rules()
    {
        $rules = parent::rules();
        return array_merge([
            [[   'product_id'], 'string'],
            [[  'category_id'], 'safe'],
        ], $rules);
    }

    public function fields()
    {
        $fields = parent::fields();
        isset($this->category_id) && $fields['category_id'] = 'category_id';
        isset($this->product_id) && $fields['product_id'] = 'product_id';
        return $fields;
    }
}
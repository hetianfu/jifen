<?php
namespace api\modules\seller\models\forms;

/**
 * @property int id
    * @author E-mail: Administrator@qq.com
	 * Create Time: 2019/03/27
	 */
class ProductQuery extends BaseQuery
{
    public $category_id;
    public $name;
    public $combo;
    public $is_on_sale;

    public $status;
    public $is_sku;
    public $sale_strategy;

    public $special_flag;

    public $supply_name;
    public function rules()
    {
        $rules = parent::rules();
        return array_merge([
            [['name' ,'sale_strategy','supply_name' ], 'string'],
            [['combo','status','is_on_sale','is_sku','special_flag'], 'integer'],
            [[ 'category_id'], 'safe'],
        ], $rules);
    }
    public function fields()
    {
        $fields = parent::fields();
        isset($this->name) && $fields['name'] = 'name';

        isset($this->supply_name) && $fields['supply_name'] =  'supply_name';
        isset($this->category_id) && $fields['category_id'] =  'category_id';
        isset($this->combo) && $fields['combo'] = 'combo';
        isset($this->status) && $fields['status'] = 'status';
        isset($this->is_on_sale) && $fields['is_on_sale'] = 'is_on_sale';
        isset($this->is_sku) && $fields['is_sku'] = 'is_sku';
        isset($this->sale_strategy) && $fields['sale_strategy'] = 'sale_strategy';
        isset($this->special_flag) && $fields['special_flag'] = 'special_flag';

        return $fields;
    }

}



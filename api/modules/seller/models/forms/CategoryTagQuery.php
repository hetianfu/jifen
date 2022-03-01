<?php
namespace api\modules\seller\models\forms;

/**
 * @property int id
         * @author E-mail: Administrator@qq.com
	 * Create Time: 2019/03/27
	 */
class CategoryTagQuery extends BaseQuery
{
    public $name;
    public $category_id;
    public function rules()
    {
        $rules = parent::rules();
        return array_merge([
            [['name','category_id' ], 'string'],
        ], $rules);
    }
    public function fields()
    {
        $fields  = parent::fields();
        isset($this->categoryId) &&$fields['category_id'] = 'category_id';
        return $fields;
    }

}



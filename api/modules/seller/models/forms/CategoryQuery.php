<?php
namespace api\modules\seller\models\forms;

/**
 * @property int id
         * @author E-mail: Administrator@qq.com
	 * Create Time: 2019/03/27
	 */
class CategoryQuery extends BaseQuery
{
    public $name;
    public $parent_id;
    public function rules()
    {
        $rules = parent::rules();
        return array_merge([
            [['name', ], 'string'],
            [['parent_id' ], 'safe'],
        ], $rules);
    }
    public function fields()
    {
        $fields  = parent::fields();
        $this->name &&$fields['name'] = 'name';
        isset($this->parent_id) &&$fields['parent_id'] = 'parent_id';
        return $fields;
    }

}



<?php
namespace api\modules\mobile\models\forms;

/**
 * @property string name
 * @property int status
 * @property int parent_id
 * @author E-mail: Administrator@qq.com
*  Create Time: 2019/03/27
*/
class CategoryQuery extends BaseQuery
{
    public $name;
    public $status;
    public $parentId;
    public function rules()
    {
        $rules = parent::rules();
        return array_merge([
            [['name'], 'string'],
            [['status','parentId'  ], 'integer'],
        ], $rules);
    }

    public function fields()
    {
        $fields  = parent::fields();
        isset($this->name) &&$fields['name'] = 'name';
        isset($this->parentId) && $fields['parent_id'] = 'parentId';
        isset($this->status)  &&$fields['status'] = 'status';
        unset($fields['parentId']);
        return $fields;
    }

}



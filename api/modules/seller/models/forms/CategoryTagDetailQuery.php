<?php
namespace api\modules\seller\models\forms;

/**
 * @property int id
         * @author E-mail: Administrator@qq.com
	 * Create Time: 2019/03/27
	 */
class CategoryTagDetailQuery extends BaseQuery
{
    public $name;
    public $tag_id;
    public function rules()
    {
        $rules = parent::rules();
        return array_merge([
            [['name','tag_id' ], 'string'],
        ], $rules);
    }
    public function fields()
    {
        $fields = parent::fields();
        isset($this->tag_id)&&$fields['tag_id']='tag_id';
        return $fields;
    }

}



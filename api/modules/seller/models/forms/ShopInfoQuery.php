<?php
namespace api\modules\seller\models\forms;

/**
 * @property int id
         * @author E-mail: Administrator@qq.com
	 * Create Time: 2019/03/27
	 */
class ShopInfoQuery extends BaseQuery
{
    public function rules()
    {
        $rules = parent::rules();
        return array_merge([
        ], $rules);
    }
    public function fields()
    {
        $fields  = parent::fields();

        return $fields;
    }

}



<?php
namespace api\modules\mobile\models\forms;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-05-21
 */

class WechatMessageQuery  extends BaseQuery{
     public  $name;
     public function rules(){
         $rules = parent::rules();
         return array_merge([
                [['name' ], 'string'],
            ], $rules);
         }
    public function fields(){
        $fields = parent::fields();
        return $fields;
    }
}
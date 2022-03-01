<?php
namespace api\modules\mobile\models\forms;

/**
 * Class UserFavoritesQuery
 * @package api\modules\mobile\models\forms
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-06-09 14:16
 */

class UserFavoritesQuery  extends BaseQuery{
     public  $name;
    public  $favorites_type;
    public  $favorites_id;
     public function rules(){
         $rules = parent::rules();
         return array_merge([
                [['name','favorites_type'  ,'favorites_id'], 'string'],
            ], $rules);
         }
    public function fields(){
        $fields =parent::fields();
        isset($this->favorites_type)&&$fields['favorites_type']='favorites_type';
        isset($this->favorites_id)&&$fields['favorites_id']='favorites_id';
        return $fields;
    }
}
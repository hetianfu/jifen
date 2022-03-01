<?php
namespace api\modules\seller\models\forms;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-04-23
 */

class CouponTemplateQuery  extends BaseQuery{
    public  $title;
    public  $status;
    public  $type;
     public function rules(){
         $rules = parent::rules();
         return array_merge([
                [['title' ,'type'], 'string'],
                [['status'  ], 'integer'],
            ], $rules);
         }
    public function fields(){
        $fields  = parent::fields();
        isset($this->title) &&$fields['title'] = 'title';
        isset($this->type) &&$fields['type'] = 'type';
        isset($this->status) &&$fields['status'] = 'status';

        return $fields;
    }

}
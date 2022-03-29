<?php
namespace api\modules\seller\models\forms;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-04-23
 */

class CouponQuery  extends BaseQuery{
     public  $template_id;
     public  $status;
     public function rules(){
         $rules = parent::rules();
         return array_merge([
             [['status','template_id' ], 'integer'],
            ], $rules);
         }
    public function fields(){
        $fields = parent::fields();

        isset($this->template_id)&&$fields['template_id']='template_id';
        isset($this->status)&&$fields['status']='status';
        return $fields;
    }
}
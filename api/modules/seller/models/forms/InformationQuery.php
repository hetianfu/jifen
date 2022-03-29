<?php
namespace api\modules\seller\models\forms;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-06-13
 */

class InformationQuery  extends BaseQuery{
     public  $title;
     public  $is_show;
     public  $cid;
     public function rules(){
         $rules = parent::rules();
         return array_merge([
                [['title' ], 'string'],
                [['is_show','cid' ], 'integer'],
            ], $rules);
         }
    public function fields(){
        $fields = parent::fields();
        isset($this->is_show)&&$fields['is_show']='is_show';
        isset($this->title)&&$fields['title']='title';
        isset($this->cid)&&$fields['cid']='cid';

        return $fields;
    }
}
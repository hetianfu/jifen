<?php
namespace api\modules\seller\models\forms;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-04-28
 */

class SystemConfigTabQuery  extends BaseQuery{
    public  $name;
    public  $title;
    public  $status;
    public  $type;
    public  $info;
    public function rules(){
         $rules = parent::rules();
         return array_merge([
                [['name','title' ], 'string'],
                [['status','type' ,'info'], 'integer'],
            ], $rules);
         }
    public function fields(){
        $fields  =parent::fields();
        isset($this->name) &&  $fields['name']='name';
        isset($this->title) &&  $fields['title']='title';
        isset($this->status) && $fields['status']='status';
        isset($this->type) &&$fields['type']='type';
        isset($this->info) && $fields['info']='info';

        return $fields;
    }


}
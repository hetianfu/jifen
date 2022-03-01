<?php
namespace api\modules\seller\models\forms;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-04-28
 */

class SystemGroupDataQuery  extends BaseQuery{
     public  $name;
     public  $gid;
     public  $status;
     public function rules(){
         $rules = parent::rules();
         return array_merge([
                [['name' ], 'string'],
                [['gid','status'], 'integer'],
            ], $rules);
         }
    public function fields(){
        $fields = parent::fields();
        isset($this->gid)&&$fields['gid']='gid';
        isset($this->status)&&$fields['status']='status';
        return $fields;
    }
}
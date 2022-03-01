<?php
namespace api\modules\seller\models\forms;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-04-28
 */

class SystemConfigQuery  extends BaseQuery{
     public  $menu_name;
     public  $config_tab_id;
     public  $status;
     public function rules(){
         $rules = parent::rules();
         return array_merge([
                [['menu_name','config_tab_id' ], 'string'],
                [['status'  ], 'integer'],
            ], $rules);
         }
    public function fields(){
        $fields  = [];
        isset($this->menu_name) &&  $fields['menu_name']='menu_name';
        isset($this->config_tab_id) &&  $fields['config_tab_id']='config_tab_id';
        isset( $this->status) &&  $fields['status']='status';

        return $fields;
    }


}
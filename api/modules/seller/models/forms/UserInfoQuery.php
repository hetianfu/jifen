<?php
namespace api\modules\seller\models\forms;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-04-23
 */

class UserInfoQuery  extends BaseQuery{
    public  $nick_name;
    public  $telephone;
    public  $is_sales_person;
    public  $identify;
    public  $is_vip;
    public  $last_log_in_ip;
    public  $source_id;
    public function rules(){
         $rules = parent::rules();
         return array_merge([
                [['nick_name','telephone','last_log_in_ip' ], 'string'],
                [['is_sales_person','identify','is_vip','source_id'], 'integer'],
            ], $rules);
         }
    public function fields(){
        $fields = parent::fields();
        isset($this->nick_name)&&$fields['nick_name']='nick_name';
        isset($this->telephone)&&$fields['telephone']='telephone';
        isset($this->is_sales_person)&&$fields['is_sales_person']='is_sales_person';
        isset($this->identify)&&$fields['identify']='identify';
        isset($this->is_vip)&&$fields['is_vip']='is_vip';
        isset($this->last_log_in_ip)&&$fields['last_log_in_ip']='last_log_in_ip';
        isset($this->source_id)&&$fields['source_id']='source_id';
        return $fields;
    }
}
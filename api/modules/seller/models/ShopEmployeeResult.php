<?php

namespace api\modules\seller\models;
use yii\base\Model;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2019/04/24
 */
class ShopEmployeeResult extends Model
{
	 /** 
	  *
	  */
	public  $id; 
	 /** 
	  *店铺ID
	  */
	public  $shop_id;
    public  $shopId;
	 /** 
	  *用户Id
	  */
	public  $openId;
    public  $name;
	 /** 
	  *员工编码
	  */
	public  $employeeNumber;

    public  $sex;
    public  $status;
    public  $email;
    /**
	  *员工镜像
	  */
	public  $userSnap;
	 /** 
	  *员工预留电话
	  */
	public  $telephone; 
	 /** 
	  *部门Id
	  */
	public  $departmentIds;

    public  $mpOpenId;
    public  $mpSendMsg;

    public  $createTime;


    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['id'],'required','on' => [ 'update' ]],
            [['id','shop_id','open_id','union_id','mp_open_id','msg_type','role','name','employee_number','email','user_snap','telephone',],'string','on' => [ 'default','update']],
            [['mp_send_msg','sex','create_time','update_time', ], 'integer','on' => [ 'default','update']],
            [[],'safe','on' => [ 'default','update']],
        ], $rules);
    }
    public function fields()
    {
        $fields = parent::fields(); 
        $fields['shopId'] = 'shop_id';

        return $fields;
    } 
    
	
  
}



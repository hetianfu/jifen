<?php
namespace api\modules\seller\models\forms;
/**
 * This is the model class for table "{{%shop_department_employee}}".
 *
 * @property string $id  MD5(部门--员工);
 * @property string $department_id departmentId 部门Id;
 * @property string $employee_id employeeId 员工Id;
 * @property string $shop_id shopId 店铺id;
 * @property int  $create_time createTime ;
 * @property int  $update_time updateTime ;
 */
class ShopDepartmentEmployeeModel extends Base{
    public static function tableName()
    {
        return '{{%shop_department_employee}}';
    }

    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['id'],'required','on' => [ 'update' ]],
            [['id','department_id','employee_id','shop_id',],'string','on' => [ 'default','update']],
            [['create_time','update_time', ], 'integer','on' => [ 'default','update']],
            [[],'safe','on' => [ 'default','update']],
        ], $rules);
    }


    public function fields(){
        $fields = parent::fields();
        return $fields;
    }

    public function setAttributes($values, $safeOnly = true) {
        parent::setAttributes($values, $safeOnly);
    }
}


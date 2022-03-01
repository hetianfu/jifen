<?php
namespace api\modules\seller\models\forms;
/**
 * This is the model class for table "{{%task_task}}".
 *
 * @property int  $id  ;
 * @property string $name  ;
 * @property  $level  ;
 * @property string $dependency_task_id dependencyTaskId ;
 * @property  $dependency_status dependencyStatus ;
 * @property string $spec  ;
 * @property  $protocol  ;
 * @property string $command  ;
 * @property  $http_method httpMethod ;
 * @property int  $timeout  ;
 * @property  $multi  ;
 * @property  $retry_times retryTimes ;
 * @property int  $retry_interval retryInterval ;
 * @property  $notify_status notifyStatus ;
 * @property  $notify_type notifyType ;
 * @property string $notify_receiver_id notifyReceiverId ;
 * @property string $notify_keyword notifyKeyword ;
 * @property string $tag  ;
 * @property string $remark  ;
 * @property  $status  ;
 * @property  $created  ;
 * @property  $deleted  ;
 */
class TaskTaskModel extends Base{
    public static function tableName()
    {
        return '{{%task_task}}';
    }
    public $created_at;
    public $updated_at;
    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['id'],'required','on' => [ 'update' ]],
            [['name','spec','command','tag','remark',],'string','on' => [ 'default','update']],
            [['id','http_method'  ], 'integer','on' => [ 'default','update']],
        ], $rules);
    }


    public function fields(){
        $fields = parent::fields();
        return $fields;
    }

    public function setAttributes($values, $safeOnly = true) {
        unset($values['updated_at']);        unset($values['created_at']);
        parent::setAttributes($values, $safeOnly);
    }
}


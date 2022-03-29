<?php
namespace api\modules\mobile\models\forms;
/**
 * This is the model class for table "{{%user_info}}".
 *
 * @property string $id  用户真实Id;
 * @property int  $amount  钱包余额; 
 * @property int  $cost_amount costAmount 消费累计;
 * @property int  $cost_number costNumber 消费次数累计;
 * @property int  $total_score totalScore 总积分;
 * @property int $lock_score 锁定积分
 * @property string $level_id levelId 等级;
 * @property string $head_img headImg 用户头像;
 * @property string $nick_name nickName 用户昵称;
 * @property string $telephone  电话号码;
 * @property string $birth_day birthDay 会员生日;
 * @property int  $sex  0-女 1男;
 * @property int  $status  1正常 0禁用
 * @property string $code  用户编号;
 * @property string $parent_id parentId 引荐人;
 * @property string $parent_type parentType 上家类开型，店铺-SHOP/ 个人- PERSON;
 * @property string $project_id projectId 关注进入的活动Id;
 * @property int  $is_resource isResource 0未认证，1认证;
 * @property int  $is_sales_person isSalesPerson 是否推广员 1 是;
 * @property string $city  城市;
 * @property int  $created_at createdAt ;
 * @property int  $updated_at updatedAt ;
 * @property string $union_id;
 * @property string $mini_app_open_id;
 * @property int $last_log_in_at;
 * @property mixed|null source_id
 * @property mixed|null last_log_in_ip
 * @property array|mixed|null open_id
 * @property array|mixed|null phone_mode
 */
class UserInfoModel extends Base{

    public $contribution;
    public $vip_name;
    public static function tableName()
    {
        return '{{%user_info}}';
    }

    public function rules(){
        $rules = parent::rules();
        return array_merge([
            [['id'],'required','on' => [ 'update' ]],
            [['id','phone_mode','level_id','head_img','nick_name','telephone','birth_day', 'code','status','parent_id','parent_type','project_id','merchant_id','source_path','city','mini_app_open_id','open_id','union_id'],'string','on' => [ 'default','update']],
            [[ 'cost_number','total_score','lock_score','sex','is_resource','is_sales_person','last_log_in_at' ,'is_vip'], 'integer','on' => [ 'default','update']],
            [['amount','cost_amount', 'charge_amount' ,'identify','source_id' ], 'number','on' => [ 'default','update']],
            [['source_json','last_log_in_ip' ], 'safe','on' => [ 'default','update']],
        ], $rules);
    }

    public function fields(){
        $fields = parent::fields();
        isset($this->cost_amount)&&$fields['costAmount']='cost_amount';
        isset($this->draw_amount)&&$fields['drawAmount']='draw_amount';

          isset($this->is_vip)&&$fields['isVip']='is_vip';
        isset($this->parent_id)&&$fields['parentId']='parent_id';
        isset($this->vip_name)&&$fields['vipName']='vip_name';
        isset($this->contribution)&&$fields['contribution']='contribution';
        isset($this->head_img)&&$fields['headImg']='head_img';
        isset($this->birth_day)&&$fields['birthDay']='birth_day';
        isset($this->charge_amount)&&$fields['chargeAmount']='charge_amount';
        isset($this->is_sales_person)&&$fields['isSalesPerson']='is_sales_person';
        isset($this->last_log_in_at)&&$fields['lastLogInAt']='last_log_in_at';
        isset($this->mini_app_open_id)&&$fields['miniAppOpenId']='mini_app_open_id';
        isset($this->open_id)&&$fields['openId']='open_id';
        isset($this->nick_name)&&$fields['nickName']='nick_name';
        isset($this->source_path)&&$fields['sourcePath']='source_path';
        isset($this->source_id)&&$fields['sourceId']='source_id';
        isset($this->source_json)&&$fields['sourceJson']=  function (){
            return json_decode($this->source_json);
        };
        isset($this->union_id)&&$fields['unionId']='union_id';
        isset($this->total_score)&&$fields['totalScore']='total_score';
        unset($fields['vip_name'],$fields['contribution'],$fields['head_img'],$fields['birth_day'],$fields['charge_amount'],
            $fields['is_sales_person'],$fields['last_log_in_at'],$fields['mini_app_open_id'],$fields['open_id'],$fields['nick_name'],
            $fields['source_path'],$fields['source_id'],$fields['source_json'],$fields['union_id'],$fields['total_score'],
            $fields['total_score'],$fields['parent_id'],$fields['is_vip'],$fields['cost_amount'],$fields['draw_amount'],
        );
         return $fields;
    }

    public function setAttributes($values, $safeOnly = true) {

        parent::setAttributes($values, $safeOnly);
    }
}


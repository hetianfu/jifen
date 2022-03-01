<?php

namespace api\modules\mobile\models\forms;
use yii\behaviors\TimestampBehavior;
use \yii\db\ActiveRecord;

class Base extends ActiveRecord
{


    const SCENARIO_UPDATE = 'update';
    const SCENARIO_RESULT= 'result';

    public $page;
    public $limit;
    public $containsTotalCount = 'true';


    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
        ];
    }
    public function rules()
    {
        return [
            [['created_at','updated_at'], 'safe'],
            [['page','limit'], 'integer'],
            [['containsTotalCount', ], 'string'],
        ];
    }

    public function fields()
    {
        $fields = parent::fields();
        $fields['createdAt']='created_at';
        $fields['updatedAt']='updated_at';
        unset($fields['created_at'],$fields['updated_at'],$fields['limit'],$fields['page'],$fields['containsTotalCount']);

        return $fields;
    }


    public function extraFields()
    {
        $extraFields = parent::extraFields();
        $extraFields['limit'] = function () {
            return empty($this->limit) ? 10 : $this->limit;
        };
        $extraFields['page'] = function () {
            return empty($this->page) ? 1 : $this->page;
        };
        $extraFields['containsTotalCount'] = function () {
            return  $this->containsTotalCount ? 'true' : 'false';
        };
        return $extraFields;
    }
    public function scenario(){
        $scenarios = parent::scenarios();
        $scenarios['default'] ;
        $scenarios['update']  ;
        return $scenarios;
    }
    /**
     * @param int $number
     * @param string $date
     * @param string $timeStr
     * @return false|string
     */
    public static function formatTime($number = 0, $date ='', $timeStr='+0 day'){
        if($number){
            $numberString = str_pad($number, 2, "0", STR_PAD_LEFT);
            if($date){
                return date("Y-m-d " .$numberString . ":00:00", strtotime($timeStr, strtotime($date)));
            }else{
                return date("Y-m-d " . $numberString . ":00:00", strtotime($timeStr));
            }
        } else {
            return date("Y-m-d H:i:s", strtotime($timeStr));
        }
    }


    /**
    　　* 下划线转驼峰
    　　* 思路:
    　　* step1.原字符串转小写,原字符串中的分隔符用空格替换,在字符串开头加上分隔符
    　　* step2.将字符串中每个单词的首字母转换为大写,再去空格,去字符串首部附加的分隔符.
    　　*/
    function camelize($uncamelized_words,$separator='_')
    {
        $uncamelized_words = $separator. str_replace($separator, " ", strtolower($uncamelized_words));
        return ltrim(str_replace(" ", "", ucwords($uncamelized_words)), $separator );
    }
    /**
　　* 驼峰命名转下划线命名
　　* 思路:
　　* 小写和大写紧挨一起的地方,加上分隔符,然后全部转小写
　　*/
    function uncamelize($camelCaps,$separator='_')
    {
        return strtolower(preg_replace('/([a-z])([A-Z])/', "$1" . $separator . "$2", $camelCaps));
    }


}

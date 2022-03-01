<?php
namespace api\modules\seller\models\forms;

use yii\base\Model;

/**
 * @property mixed showOrer
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-03-25
 */

class ProductSpecRequest  extends Model {
    public $id;
    public $name;
    public $show_order;
    public $spec_list;
    public $merchant_id;
    public function rules(){
     $rules = parent::rules();
     return array_merge([
            [['id','name','merchant_id' ], 'string'],
            [['spec_list','show_order' ], 'safe'],
        ], $rules);
     }
    public function fields(){
        $fields = parent::fields();
        return $fields;
    }
}
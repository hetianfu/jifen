<?php

namespace api\modules\mobile\models\forms;

use fanyou\enums\entity\SystemGroupEnum;
use fanyou\enums\QueryEnum;

/**
 * @property int id
 * @author E-mail: Administrator@qq.com
 * Create Time: 2019/03/27
 */
class ProductQuery extends BaseQuery
{

  public $name;
  public $type;
  public $isOnSale = 1;
  public $categoryId;
  public $saleStrategy = SystemGroupEnum::NORMAL_TYPE;
  public $isHot;

  public $special_flag = 0;
  public $needScore;
  public $gtNeedScore;
  public function rules()
  {
    $rules = parent::rules();
    return array_merge([
      [['name', 'categoryId', 'saleStrategy','type'], 'string'],
      [['isOnSale', 'isHot','special_flag','needScore','gtNeedScore'], 'safe'],
    ], $rules);
  }

  public function fields()
  {
    $fields = parent::fields();
    isset($this->id) && $fields['id'] = 'id';
    isset($this->name) && $fields['name'] = 'name';
    isset($this->type) && $fields['type'] = 'type';
    isset($this->categoryId) && $fields['category_id'] = 'categoryId';
    isset($this->needScore) && $fields['need_score'] = 'needScore';
    isset($this->gtNeedScore) && $fields['need_score'] =function (){
      return QueryEnum::GT.$this->gtNeedScore;
    };

    isset($this->isHot) && $fields['is_hot'] = 'isHot';
    isset($this->isOnSale) && $fields['is_on_sale'] = 'isOnSale';
    isset($this->saleStrategy) && $fields['sale_strategy'] = 'saleStrategy';
    isset($this->special_flag) && $fields['special_flag'] = 'special_flag';
    unset($fields['isOnSale'],$fields['saleStrategy'],$fields['categoryId'],$fields['type']);
    return $fields;
  }

}



<?php

namespace api\modules\mobile\models;

use yii\base\Model;

/**
 * This is the model class for table "{{%user_shop_cart}}".
 *
 * @property string $id  ;
 * @property string $user_id userId ;
 * @property string $product_id productId 商品;
 * @property int $number  数量;
 * @property  $created_at createdAt ;
 * @property  $updated_at updatedAt ;
 */
class UserShopCartResult extends Model
{
    public $id;
    public $userId;
    public $productId;
    public $productInfo;
    public $skuInfo;
    public $number;

    public function fields()
    {
        $fields = parent::fields();

        $fields['productInfo'] = function () {
            $model = new CartProductResult($this->skuInfo);
            $model->setAttributes($this->productInfo, false);
            return $model->toArray();
        };

        unset( $fields['skuInfo']);
        return $fields;
    }

}


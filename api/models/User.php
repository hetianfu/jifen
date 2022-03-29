<?php

namespace api\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * 实现User组件中的身份识别类 参见 yii\web\user
 * This is the model class for table "{{%user}}".
 *
 */
class User  implements IdentityInterface
{


    public static function findIdentity($id)
    {
        echo 11;exit;
    }


    public static function findIdentityByAccessToken($token, $type = null)
    {
        return ['access-token'=>'abcabc'];
    }

    public function getId()
    {
        echo 11;exit;
    }

    public function getAuthKey()
    {
       return '31231';
    }

    public function validateAuthKey($authKey)
    {
        echo 11;exit;
    }
}

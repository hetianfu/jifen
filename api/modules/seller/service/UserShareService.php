<?php

namespace api\modules\seller\service;

use api\modules\mobile\models\forms\UserShareModel;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-05-30
 */
class UserShareService
{

/*********************UserShare模块服务层************************************/

    /**
     * 清空商品分享图
     * @param $productId
     * @return int
     */
    public function cleanShareImg($productId)
    {
        $array=['GOODS_INFO','SCORE','NORMAL','SECKILL','PINK','USER_INFO'];

        return UserShareModel::updateAll( ['share_url'=>null],['AND',['key_id'=>$productId],['in','key_type',$array]]);
    }

}
/**********************End Of UserShare 服务层************************************/


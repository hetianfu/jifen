<?php

namespace fanyou\tools\helpers;

/**
 * Class TreeHelper
 * @package common\helpers
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-06-20 16:15
 */
class TreeHelper
{
    /**
     * @return string
     */
    public static function prefixTreeKey($id)
    {
        return "tr_$id ";
    }

    /**
     * @return string
     */
    public static function defaultTreeKey()
    {
        return 'tr_0 ';
    }
}
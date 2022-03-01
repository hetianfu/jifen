<?php

namespace fanyou\components\systemDrive;


/**
 * ss
 *
 * @author leedong
 *
 */
class ArrayColumn
{

    public  static  function getSystemConfigValue($array): ?array
    {
        if (!empty($array)) {
            return array_column($array, 'value', 'menu_name');;
        }else{
             return $array;
        }
    }

}


<?php

namespace fanyou\enums\entity;

use fanyou\enums\BaseEnum;

/**
 * Class AuthItemEnum
 * @package fanyou\enums\entity
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-06-20 16:11
 */
class AuthItemEnum extends BaseEnum
{

    const ROLELIST = 'roleList';
    const ITEMLIST = 'itemList';

    const EXTEND_ITEM = 'extendItem';

    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [

            self::ROLELIST => '角色列表',
            self::ITEMLIST => '菜单列表',
        ];
    }
}
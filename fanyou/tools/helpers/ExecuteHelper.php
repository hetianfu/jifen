<?php

namespace fanyou\tools\helpers;

use yii\web\NotFoundHttpException;

/**
 * Class ExecuteHelper
 * @package fanyou\tools\helpers
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-07-10 10:42
 */
class ExecuteHelper
{
    /**
     * @param string $classPath 实例化类名路径
     * @param string $method 方法
     * @param array $params 参数
     * @throws NotFoundHttpException
     */
    public static function map($classPath, $method, $params)
    {
        if (!class_exists($classPath)) {
            throw new NotFoundHttpException($classPath . '未找到');
        }

        /* @var $class \common\interfaces\AddonWidget */
        $class = new $classPath;
        if (!method_exists($class, $method)) {
            throw new NotFoundHttpException($classPath . '/' . $method . ' 方法未找到');
        }

        return $class->run($params);
    }
}
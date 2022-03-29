<?php

return [
    'name' => 'fanyoushop',
    'version' => '1.2.7',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'language' => 'zh-CN',
    'sourceLanguage' => 'zh-cn',
    'timeZone' => 'Asia/Shanghai',
    'bootstrap' => [
        'fanyou\components\Init', // 加载默认的配置
     ],
    'components' => [

        /** ------ 格式化时间 ------ **/
        'formatter' => [
            'dateFormat' => 'yyyy-MM-dd',
            'datetimeFormat' => 'yyyy-MM-dd HH:mm:ss',
            'decimalSeparator' => ',',
            'thousandSeparator' => ' ',
            'currencyCode' => 'CNY',
        ],
        /** ------ 系统驱动配置 ------ **/
        'systemConfig' => [
            'class' => 'fanyou\components\SystemConfig',
        ],
        /** ------ 上传组件 ------ **/
        'uploadDrive' => [
            'class' => 'fanyou\components\UploadDrive',
        ],
        /** ------ 队列设置 ------ **/
        'queue' => [
            'class' =>  'yii\queue\file\Queue',
            'as log' => 'yii\queue\LogBehavior',
            'path' => '@runtime/queue',
        ],

        /** ------ 微信SDK ------ **/
        'wechat' => [
            'class' => 'fanyou\easywechat\Wechat',//'common\components\Wechat',
            'userOptions' => [],  // 用户身份类参数
            'sessionParam' => 'wechatUser', // 微信用户信息将存储在会话在这个密钥
            'returnUrlParam' => '_wechatReturnUrl', // returnUrl 存储在会话中
            'rebinds' => [
                'cache' => 'fanyou\easywechat\WechatCache',
            ]
        ],

    ],
];

<?php
return [
    //系统维护
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['seller_common/system-config'],
        'extraPatterns' => [
            'GET store-drive' => 'store-drive',

            'POST add-tab' => 'add-tab',
            'GET get-tab-page' => 'get-tab-page',
            'PATCH update-tab-by-id' => 'update-tab-by-id',
            'DELETE del-tab-by-id/<id:\S+>' => 'del-tab-by-id',

            'GET get-tab-with-value/<id:\S+>' => 'get-tab-with-value',
            'POST submit-config-by-id/<id:\S+>' => 'submit-config-by-id',

            'POST add-system-config' => 'add-system-config',
            'GET get-system-config-page' => 'get-system-config-page',
            'GET get-system-config-list' => 'get-system-config-list',
            'PATCH update-system-config-by-id' => 'update-system-config-by-id',
            'DELETE del-system-config-by-id/<id:\S+>' => 'del-system-config-by-id',

            'GET get-basic-config' => 'get-basic-config',
            'PATCH update-basic-config' => 'update-basic-config',

            'GET get-wx-mp-config' => 'get-wx-mp-config',
            'PATCH update-wx-mp-config' => 'update-wx-mp-config',
            'GET get-wx-pay-config' => 'get-wx-pay-config',
            'PATCH update-wx-pay-config' => 'update-wx-pay-config',
            'GET get-wx-mini-app-config' => 'get-wx-mini-app-config',
            'PATCH update-wx-mini-app-config' => 'update-wx-mini-app-config',


            'GET get-printer-config' => 'get-printer-config',
            'PATCH update-printer-config' => 'update-printer-config',

            'GET get-ali-oss-config' => 'get-ali-oss-config',
            'PATCH update-ali-oss-config' => 'update-ali-oss-config',

            'GET get-score-config' => 'get-score-config',
            'PATCH update-score-config' => 'update-score-config',

            'GET get-distribute-config' => 'get-distribute-config',
            'PATCH update-distribute-config' => 'update-distribute-config',

            'GET get-freight-config' => 'get-freight-config',
            'PATCH update-freight-config' => 'update-freight-config',

            'GET get-index-app-config' => 'get-index-app-config',
            'PATCH update-index-app-config' => 'update-index-app-config',

            'GET get-index-page-config' => 'get-index-page-config',
            'PATCH update-index-page-config' => 'update-index-page-config',


            'GET test-db' => 'test-db',
            'GET get-env-config' => 'get-env-config',
            'POST update-env-config' => 'update-env-config',
            'GET get-common-config' => 'get-common-config',
            'GET get-common-config-list' => 'get-common-config-list',

            'PATCH update-common-config' => 'update-common-config',

            'GET get-common-son-config' => 'get-common-son-config',
            'PATCH update-common-son-config' => 'update-common-son-config',

        ]
    ],
    //组合数据维护
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['seller_common/system-group'],
        'extraPatterns' => [

            'POST add-system-group' => 'add-system-group',
            'GET get-system-group-page' => 'get-system-group-page',
            'PATCH update-system-group-by-id' => 'update-system-group-by-id',
            'DELETE del-system-group-by-id/<id:\S+>' => 'del-system-group-by-id',

            'POST add-system-group-data' => 'add-system-group-data',
           // 'GET get-system-group-data-page' => 'get-system-group-data-page',
            'PATCH update-system-group-data-by-id' => 'update-system-group-data-by-id',
            'DELETE del-system-group-data-by-id/<id:\S+>' => 'del-system-group-data-by-id',

            'GET get-product-group-data-page' => 'get-product-group-data-page',
            'GET get-strategy-group-data-page' => 'get-strategy-group-data-page',
            'GET get-normal-group-data-page' => 'get-normal-group-data-page',

            'GET test' => 'test',

        ]
    ],
    //微信
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['seller_common/config'],
        'extraPatterns' => [

            'GET get-config-page' => 'get-config-page',
            'PATCH update-auth-role' => 'update-auth-role',
            'DELETE delete-auth-role' => 'del-auth-role',
        ]
    ],
    //微信服务事件
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['seller_common/wx-service'],
        'extraPatterns' => [

            'GET push' => 'push',

        ]
    ],

    //微信菜单
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['seller_common/wx-menu'],
        'extraPatterns' => [

            'GET get-wx-menu-list' => 'get-wx-menu-list',
            'GET get-wx-menu' => 'get-wx-menu',

            'GET get-wx-menu-by-id/<id:\S+>' => 'get-wx-menu-by-id',
            'POST save-wx-menu' => 'save-wx-menu',
            'GET syn-wx-menu' => 'syn-wx-menu',
            'PATCH update-auth-role' => 'update-auth-role',
            'DELETE delete-wx-menu' => 'del-by-id',
        ]
    ],
    //微信消息模版
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['seller_common/wx-template-msg'],
        'extraPatterns' => [

            'POST set-wx-industry' => 'set-wx-industry',
            'GET get-wx-industry' => 'get-wx-industry',

            'POST add-wx-template' => 'add-wx-template',
            'GET get-wx-template-list' => 'get-wx-template-list',
            'DELETE del-wx-template-by-id/<id:\S+>' => 'del-wx-template-by-id',
            'GET send-wx-template-msg' => 'send-wx-template-msg',

        ]
    ],
    //微信用户管理
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['seller_common/wx-user'],
        'extraPatterns' => [

            'POST set-wx-user-black' => 'set-wx-user-black',
            'POST set-wx-user-white' => 'set-wx-user-white',
            'GET get-wx-user-black-list' => 'get-wx-user-black-list',
        ]
    ],
    //微信客服管理
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['seller_common/wx-customer'],
        'extraPatterns' => [

            'POST invite-wx-customer' => 'invite-wx-customer',
            'POST add-wx-customer' => 'add-wx-customer',
            'GET update-wx-customer' => 'update-wx-customer',
            'PATCH set-wx-customer-head-img' => 'set-wx-customer-head-img',
            'DELETE del-wx-customer' => 'del-wx-customer',

            'GET create-message' => ' create-message',
            'GET send-customer-message' => 'send-customer-message',
            'GET get-wx-customer-online-list' => 'get-wx-customer-online-list',
            'GET get-wx-customer-list' => 'get-wx-customer-list',

        ]
    ],
    //小程序消息模版
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['seller_common/wx-subscribe-msg'],
        'extraPatterns' => [
            'POST add' => 'add',
            'PATCH update-by-id' => 'update-by-id',
            'DELETE del-by-id/<id:\S+>' => 'del-by-id',
            'GET get-page' => 'get-page',
            'GET get-category' => 'get-category',

           //'POST send-template-msg' => 'send-template-msg',
            //测试添加订阅模版
            'GET test-add-sub-scribe' => 'test-add-sub-scribe',

        ]
    ],
    //小程序统计
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['seller_common/wx-summary'],
        'extraPatterns' => [

            'GET summary' => 'summary',

        ]
    ],
];
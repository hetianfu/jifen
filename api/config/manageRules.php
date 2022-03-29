<?php
return [
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['manage/user'],
        'extraPatterns' => [
            'POST login' => 'login',
            'POST register' => 'register',

            'GET get-check-record-page' => 'get-check-record-page',
            'GET get-check-record-by-id/<id:\S+>' => 'get-check-record-by-id',

            'GET scan-code/<id:\S+>' => 'scan-code',
            'GET get-info-by-order/<id:\S+>' => 'get-info-by-order',
            'POST verify-code' => 'verify-code',
        ]
    ],

    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['manage/wechat'],
        'extraPatterns' => [

            'GET oauth' => 'oauth',
            'GET oauth-redirect' => 'oauth-redirect',
            'GET code-to-user' => 'code-to-user',
            'GET get-js-sdk' => 'get-js-sdk',
        ]
    ],




];
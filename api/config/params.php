<?php
return [
    //订单待支付时间
    'orderRemainTime' => 10* 3600,
    'shopUploadConfig' => [
        'baseUrl' => 'shop',
        'img' => 'shop/img',
    ],
    'categoryUploadConfig' => [
        'baseUrl' => 'category',
        'img' => 'category/img',
    ],
    'productUploadConfig' => [
        'baseUrl' => 'product',
        'img' => 'product/img',
        'video' => 'product/video',

        'reply' => 'product/reply',
        'rich-text' => 'product/rich-text'

    ],
    'informationUploadConfig' => [
        'baseUrl' => 'information',
        'img' => 'information/img',
        'video' => 'information/video',
        'rich-text' => 'information/rich-text'
    ],

    'systemConfigImage' => [
        'baseUrl' => 'system',
        'img' => 'system-config/img',
    ],
    'userImage' => [
        'baseUrl' => 'user-img',
        'head' => 'user-img/head',
        'share' => 'user-img/share',
        'product-share' => 'user-img/product-share',
    ],
    'downloadImage' => [
        'product' => 'product-img',

    ],
    'checkPermission' => [
        'base' => '/seller',
        'login' => ['/seller/logins/get-auth-key', '/seller/logins/get-auth-info','/seller/logins/is-init',
            '/seller/auth-items/get-auth-item-by-roles', '/seller/auth-items/get-currency-menu-item'],
        'common' => ['/seller/order-info/count-order-info-title', '/seller/order-info/sum-order-info-title',
            '/seller/order-info/get-order-info-page', '/seller/categories/get-category-list'
            , '/seller/auth-items/get-auth-item-list', '/seller/upload-imgs/*','/seller_common/system-config/store-drive'],

    ],

    'shareImg' => [
        'product' => [
            'fontColor' => '247,2,6',//字体颜色]
        ]
    ],
];
